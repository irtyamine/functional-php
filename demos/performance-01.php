<?php

namespace Main; // Useful for PHPStorm auto imports

use FunctionalPhp\Closure\Range;
use FunctionalPhp\Session;

require_once __DIR__.'/../vendor/autoload.php';

$session = new Session();
$session
    ->from(Range::class, ['from' => 0, 'to' => 1_000_000])
    ->then(function (int $x): \Generator {
        yield "Now, it's a string, for value $x";
    })
;

$mem = memory_get_peak_usage();
$time = microtime(true);
$session->run();
echo sprintf("Time  : %.6fs\n", microtime(true) - $time );
echo sprintf("Memory: %.2fMB\n", (memory_get_peak_usage() - $mem) / 1024 / 1024 );
