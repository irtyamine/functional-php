<?php

namespace Main; // Useful for PHPStorm auto imports

use FunctionalPhp\Closure\Range;
use FunctionalPhp\Session;

require_once __DIR__.'/../vendor/autoload.php';

$max = 100_000;
$counterA = 0;
global $td;
$td = 0;

$session = new Session();
$session
    ->from(Range::class, ['from' => 1, 'to' => $max])
    ->then(function (int $x) {
        global $counterA;
        $counterA++;

        yield "Now, it's a first string, from value $x";
    })
;

$mem = memory_get_peak_usage();
$time = microtime(true);
$session->run();
$time = microtime(true) - $time;
$mem = memory_get_peak_usage() - $mem;

echo sprintf("Time     : %.6fs\n", $time);
echo sprintf("Time diff: %.6fs (%.0f%%)\n", $td, $td === 0 ? '-' : 100 * $td/$time);
echo sprintf("Memory   : %.2fMB\n", $mem / 1024 / 1024 );
echo sprintf("Counter A: %d\n", $counterA);
