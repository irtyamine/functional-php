<?php

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;

require_once __DIR__.'/../vendor/autoload.php';

$session = new Session();

$session
    ->from(StaticArray::class, ['values' => [1, 2, 3]])
    ->then(function ($x) {
        yield $x;
        yield $x + 10;
    })
    ->then(function (int $x): string {
        return 'Now, it\'s '.$x;
    })
    ->then('dump')
;

$session->run();
