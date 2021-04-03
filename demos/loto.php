<?php

namespace Main;

use FunctionalPhp\Closure\Range;
use FunctionalPhp\Session;

/* @var Session $session */

$start = $session
    ->from(Range::class, ['from' => 1, 'to' => 10000])
    ->then(function () {
        return random_int(1, 49);
    });

$start->then(function (int $x): string {
    return 'Now, it\'s '.$x;
});

$start->then(function (int $x): string {
    return 'Now, it\'s '.$x;
})->then('strtoupper')->then('strtolower');

$start->then(function (int $x): string {
    return 'Now, it\'s '.$x;
});
