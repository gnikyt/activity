<?php

namespace Activity;

use TylerKing\Activity\Activity as BaseActivity;

class Status extends BaseActivity
{
    public function render()
    {
        $name   = $this->getCreator();
        $time   = $this->getTime()->format('F jS, Y g:i A');
        $status = $this->getStatus();

        return "{$name} on {$time} said... '{$status}'";
    }
}
