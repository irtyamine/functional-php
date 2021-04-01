<?php

namespace FunctionalPhp\Builder;

class ClosureReader
{
    /**
     * @return bool[]
     */
    public function read(\Closure $closure): array
    {
        $refl = new \ReflectionFunction($closure);
        $input = $refl->getNumberOfParameters() === 0;
        $return = $refl->getReturnType();
        if (!$return) {
            throw new \LogicException('A closure misses it\'s return type');
        }

        /** @var \ReflectionNamedType $return */
        $output = $return->getName() === 'Generator';

        return [$input, $output];
    }
}
