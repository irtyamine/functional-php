<?php

namespace FunctionalPhp\Closure;

class ArrayCombine
{
    /**
     * @var array<int, int|string>
     */
    private array $keys;

    /**
     * @param array<int, int|string> $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @param array<int, int|string> $values
     *
     * @return \Generator<int|string, mixed>
     */
    public function __invoke(array $values): \Generator
    {
        yield array_combine($this->keys, $values);
    }
}
