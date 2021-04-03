<?php

namespace Main; // Useful for PHPStorm auto imports

use FunctionalPhp\Closure\StaticArray;
use FunctionalPhp\Session;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\ErrorHandler\Debug;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable();

$verbosity = OutputInterface::VERBOSITY_DEBUG;
$logger = new ConsoleLogger(new ConsoleOutput($verbosity));
$session = new Session(logger: $logger);

$values = ['hello', 'world', 'and', 'others', 'too', '=)'];
$source = $session->from(StaticArray::class, ['values' => $values]);

$source->then('strtoupper')->then('dump');

$session->run();
