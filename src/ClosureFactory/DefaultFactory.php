<?php

namespace FunctionalPhp\ClosureFactory;

use FunctionalPhp\Closure\ArrayCombine;
use FunctionalPhp\Closure\Callback;
use FunctionalPhp\Closure\JsonDecode;
use FunctionalPhp\Closure\JsonEncode;
use FunctionalPhp\Closure\StaticArray;

class DefaultFactory implements ClosureFactoryInterface
{
    private const DEFAULT_CLASSES = [
        ArrayCombine::class,
        Callback::class,
        JsonDecode::class,
        JsonEncode::class,
        StaticArray::class,
    ];

    public function supports(string $type, array $options = []): bool
    {
        if (!in_array($type, self::DEFAULT_CLASSES, true)) {
            return false;
        }

        return true;
    }

    public function create(string $type, array $options = []): \Closure
    {
        $callable = new $type(...$options);

        return \Closure::fromCallable($callable);
    }
}
