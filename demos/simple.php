<?php

namespace Main;

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;
use Symfony\Component\ErrorHandler\Debug;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable();

$session = new Session();

$values = ['hello', 'world', 'and', 'others', 'too', '=)'];
$source = $session->from(StaticArray::class, ['values' => $values]);

$source->then('dump');

$session->run();
