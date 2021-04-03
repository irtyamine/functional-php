<?php

namespace FunctionalPhp\Closure;

class Range
{
    private int $from;
    private int $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return \Generator<mixed>
     */
    public function __invoke(): \Generator
    {
        for ($i = $this->from; $i <= $this->to; $i++) {
            yield $i;
        }
    }
}
