<?php

namespace FunctionalPhp;

interface NodeBuilderInterface
{
    public function getClosure(): \Closure;

    /**
     * @param array<string, mixed> $options
     */
    public function write(string $type, array $options = []): NodeBuilderInterface;

    /**
     * @param array<int, int|string> $keys
     */
    public function combineKeys(array $keys): NodeBuilderInterface;

    public function deserialize(string $class): NodeBuilderInterface;
    public function callback(callable $param): NodeBuilderInterface;
    public function jsonDecode(): NodeBuilderInterface;
    public function jsonEncode(): NodeBuilderInterface;
}
