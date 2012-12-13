<?php

namespace Activity;

use TylerKing\Activity\Activity as BaseActivity;

class PhotoLike extends BaseActivity
{
    public function render()
    {
        $name   = $this->getCreator();
        $time   = $this->getTime()->format('F jS, Y g:i A');
        $photo  = $this->getPhoto();

        return "{$name} on {$time} liked a photo <img src=\"{$photo}\" />";
    }
}
