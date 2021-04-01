<?php

namespace FunctionalPhp\Closure;

class Callback
{
    /** @var callable */
    private mixed $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param array<mixed> $args
     * @return \Generator<mixed>
     */
    public function __invoke(...$args): \Generator
    {
        yield from call_user_func_array($this->callback, $args);
    }
}
