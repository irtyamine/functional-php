<?php

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;

require_once __DIR__.'/../vendor/autoload.php';

$session = new Session();

$from = $session->from(StaticArray::class, ['values' => [1, 2, 3]]);

$from->then(function ($x) {
        yield $x;
        yield $x + 1;
        yield $x + 2;
    })
    ->then(function (int $x): string {
        return 'Now, it\'s '.$x;
    })
;

$from->then('dump');

$session->run();
