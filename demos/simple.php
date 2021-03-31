<?php

namespace Main;

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;

require_once __DIR__.'/../vendor/autoload.php';

$session = new Session();

$session
    ->from(StaticArray::class, ['values' => [1, 2, 3, 4, 5, 6]])
    ->callback('dump')
;

$session->run();
