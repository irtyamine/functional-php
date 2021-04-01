<?php

namespace FunctionalPhp\ClosureFactory;

class CallableFactory implements ClosureFactoryInterface
{
    public function supports(mixed $type, array $options = []): bool
    {
        return is_callable($type);
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        if ($type instanceof \Closure) {
            return $type;
        }

        return \Closure::fromCallable($type);
    }
}
