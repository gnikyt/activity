<?php

namespace TylerKing\Activity;

class Feed
{
    const COUNT = 100;

    protected $redis,
              $uid;

    public function __construct(\Predis\Client $redis)
    {
        $this->redis = $redis;

        return $this;
    }

    public function setUser($uid)
    {
        $this->uid = (int) $uid;
    }

    public function get($count = self::COUNT)
    {
        $results = $this->redis->zrevrange("feed:{$this->uid}", 0, $count);
        if ($results) {
            $results = array_map(function($result) {
                $item = json_decode($result);

                $issuer = new $item->Issuer;
                $issuer->load($item);

                return $issuer;
            }, $results);

            return $results;
        }

        return null;
    }

    public function getJson($count = self::COUNT)
    {
        $results = $this->redis->zrevrange("feed:{$this->uid}", 0, $count);
        if ($results) {
            array_walk($results, function(&$key, $value) {
                $key = json_decode($key);
            });

            return $results;
        }

        return null;
    }

    public function count()
    {
        return (int) $this->redis->zcount("feed:{$this->uid}", '-inf', '+inf');
    }

    public function trim($index = self::COUNT)
    {
        $key        = "feed:{$this->uid}";
        $last_index = $this->redis->zcard($key);

        if ($last_index >= $index) {
            $_index = $index-1;
            $result = $this->redis->zrevrange($key, $_index, $_index, ['with_scores' => true]);
            $score  = $this->redis->zscore($key, $result[0]);

            if ($score !== null) {
                $this->redis->zremrangebyscore($key, '-inf', "({$score}");
            }
        }
    }
}
