<?php

namespace TylerKing\Activity;

class Broadcast
{
    protected $redis,
              $activity,
              $to,
              $key;

    public function __construct(\Predis\Client $redis, $key = 'feed')
    {
        $this->redis    = $redis;
        $this->activity = null;
        $this->to       = [];
        $this->key      = $key;

        return $this;
    }

    public function setActivity(\TylerKing\Activity\ActivityInterface $activity)
    {
        $this->activity = $activity;

        return $this;
    }

    public function getActivity()
    {
        return $this->activity;
    }

    public function setTo(array $to)
    {
        $this->to = $to;

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function flush()
    {
        $time = $this->activity->getTime()->getTimestamp();
        $json = $this->activity->serialize();
        $to   = $this->to;

        foreach ($to as $user) {
            $this->redis->zadd("{$this->key}:{$user}", $time, $json);
        }

        return $this;
    }
}
