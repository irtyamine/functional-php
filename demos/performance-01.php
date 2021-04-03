<?php

use FunctionalPhp\Closure\Range;
use FunctionalPhp\Session;

/* @var Session $session */

$max = 100_000;
$counterA = 0;

$session
    ->from(Range::class, ['from' => 1, 'to' => $max])
    ->then(function (int $x) {
        global $counterA;
        $counterA++;

        yield "Now, it's a first string, from value $x";
    })
;
