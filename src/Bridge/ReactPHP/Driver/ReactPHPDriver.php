<?php

namespace FunctionalPhp\Bridge\ReactPHP\Driver;

use FunctionalPhp\Driver\DriverInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class ReactPHPDriver implements DriverInterface
{
    private LoopInterface $loop;

    public function __construct(?LoopInterface $loop = null)
    {
        $this->loop = $loop ?? Factory::create();
    }

    public function future(callable $callable): void
    {
        $this->loop->futureTick($callable);
    }

    public function read(mixed $stream, callable $callable): void
    {
        $this->loop->addReadStream($stream, $callable);
    }

    public function time(int|float $delay, callable $callable): void
    {
        $this->loop->addTimer($delay, $callable);
    }

    public function interval(int|float $interval, callable $callable): void
    {
        $this->loop->addPeriodicTimer($interval, $callable);
    }

    public function start(): void
    {
        // Nothing to do
    }

    public function stop(): void
    {
        $this->loop->stop();
    }

    public function run(): void
    {
        $this->loop->run();
    }
}
