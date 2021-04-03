<?php

namespace FunctionalPhp\ClosureFactory;

use FunctionalPhp\Closure\ArrayCombine;
use FunctionalPhp\Closure\Callback;
use FunctionalPhp\Closure\JsonDecode;
use FunctionalPhp\Closure\JsonEncode;
use FunctionalPhp\Closure\Range;
use FunctionalPhp\Closure\StaticArray;

class ConstructorFactory implements ClosureFactoryInterface
{
    private const DEFAULT_CLASSES = [
        ArrayCombine::class,
        Callback::class,
        JsonDecode::class,
        JsonEncode::class,
        Range::class,
        StaticArray::class,
    ];

    public function supports(mixed $type, array $options = []): bool
    {
        return in_array($type, self::DEFAULT_CLASSES, true);
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        return \Closure::fromCallable(new $type(...$options));
    }
}
