<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Activity', __DIR__);

use Predis\Client;
use TylerKing\Activity\Broadcast;
use TylerKing\Activity\Feed;
use Activity\Status;
use Activity\PhotoLike;

$redis = new Client;

// Setup some friends and the current user (you can use a database for this).
$redis->del('uid:1:friends');
$redis->del('feed:2');

$redis->lpush('uid:1:friends', 2);
$redis->lpush('uid:1:friends', 3);

$friends = $redis->lrange('uid:1:friends', 0, -1);

// Make a status.
$status = new Status($redis);
$status->setCreator(1);
$status->setStatus('Hey, I just joined this site!');

// Broadcast status to user's friends...
$broadcast = new Broadcast($redis);
$broadcast->setActivity($status);
$broadcast->setTo($friends);
$broadcast->flush();

// Make a photo like.
$like = new PhotoLike($redis);
$like->setCreator(1);
$like->setPhotoId(5);
$like->setPhoto('http://i.imgur.com/pcS7V.jpg');

// Broadcast photo like to user's friends...
$broadcast = new Broadcast($redis);
$broadcast->setActivity($like);
$broadcast->setTo($friends);
$broadcast->flush();

// Show friend #2's feed...
$feed  = new Feed($redis);
$feed->setUser(2);
$items = $feed->get();

if (null === $items) {
    echo 'No news for you!'.PHP_EOL;
} else {
    foreach ($items as $item) {
        echo $item->render().PHP_EOL;
    }
}
