<?php

use App\Console\NotLazyContainer;

function container(): NotLazyContainer
{
    static $app;
    return $app ?: $app = new NotLazyContainer(include_once __DIR__ . '/../config.php');
}
