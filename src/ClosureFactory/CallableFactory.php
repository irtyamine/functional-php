<?php

namespace FunctionalPhp\ClosureFactory;

use FunctionalPhp\Closure\Callback;

class CallableFactory implements ClosureFactoryInterface
{
    public function supports(mixed $type, array $options = []): bool
    {
        return is_callable($type);
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        if ($type instanceof \Closure) {
            $returnType = (new \ReflectionFunction($type));
            if ($returnType && $returnType instanceof \ReflectionNamedType && $returnType->getName() === 'Generator') {
                return $type;
            }
        }

        return \Closure::fromCallable(new Callback($type));
    }
}
