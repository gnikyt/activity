<?php

namespace TylerKing\Activity;

class Broadcast
{
    protected $redis,
              $activity,
              $to;

    public function __construct(\Predis\Client $redis)
    {
        $this->redis    = $redis;
        $this->activity = null;
        $this->to       = [];

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
            $this->redis->zadd("feed:{$user}", $time, $json);
        }
    }
}
