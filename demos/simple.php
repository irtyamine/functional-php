<?php

namespace Main;

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;
use Symfony\Component\ErrorHandler\Debug;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable();

$session = new Session();

$session
    ->from(StaticArray::class, ['values' => [1, 2, 3, 4, 5, 6]])
    ->then('dump')
;

$session->run();
