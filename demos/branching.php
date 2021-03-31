<?php

use FunctionalPhp\SessionInterface;

/** @var SessionInterface $session */
$values = $session->read('array', ['values' => [1, 2, 3]]);

$values->write('console');

$values
    ->callback(function (int $x) {
        yield $x;
        yield $x + 1;
        yield $x + 2;
    })
    ->write('csv', ['file' => 'output.csv'])
;
