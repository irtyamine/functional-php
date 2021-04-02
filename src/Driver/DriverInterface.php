<?php

namespace FunctionalPhp\Driver;

interface DriverInterface
{
    public function future(callable $callable): void;
    public function read(mixed $stream, callable $callable): void;
    public function time(int|float $delay, callable $callable): void;
    public function interval(int|float $interval, callable $callable): void;
    public function stop(): void;
    public function run(): void;
}
