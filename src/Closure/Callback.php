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
        $result = call_user_func_array($this->callback, $args);

        if ($result instanceof \Generator) {
            return $result;
        }

        yield $result;
    }
}
