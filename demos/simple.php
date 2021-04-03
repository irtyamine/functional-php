<?php

namespace Main;

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;

/** @var Session $session */

$values = ['hello', 'world', 'and', 'others', 'too', '=)'];
$source = $session->from(StaticArray::class, ['values' => $values]);

$source->then('strtoupper')->then('dump');
