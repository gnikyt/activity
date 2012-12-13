<?php

namespace TylerKing\Activity;

interface ActivityInterface
{
    function serialize();
    function load($json);
}
