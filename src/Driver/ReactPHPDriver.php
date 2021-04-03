<?php

namespace FunctionalPhp\Driver;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;

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

    /**
     * @param int|float $interval
     * @param callable $callable
     */
    public function interval(int|float $interval, callable $callable): void
    {
        $this->loop->addPeriodicTimer($interval, function (TimerInterface $timer) use ($callable) {
            $result = $callable();
            if ($result === false) {
                $this->loop->cancelTimer($timer);
            }
        });
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
