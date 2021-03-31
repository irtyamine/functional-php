<?php

use FunctionalPhp\SessionInterface;

/** @var SessionInterface $session */
$session
    ->read('array', ['values' => [1, 2, 3]])

    // Send multiple values per message
    ->callback(function (int $x) {
        yield $x;
        yield $x + 1;
        yield $x + 2;
    })
    // Or return a single
    ->callback(function (int $x): string {
        return 'Now, it\'s '.$x;
    })

    ->write('console')
;
