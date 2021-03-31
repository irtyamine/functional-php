<?php

namespace FunctionalPhp\Closure;

class StaticArray
{
    /**
     * @var array<int|string, mixed> $values
     */
    private array $values;

    /**
     * @param array<int|string, mixed> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return \Generator<int|string, mixed> $values
     */
    public function __invoke(): \Generator
    {
        yield from $this->values;
    }
}
