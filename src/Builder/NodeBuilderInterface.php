<?php

namespace FunctionalPhp\Builder;

/**
 * Interface used to describe a functional program.
 */
interface NodeBuilderInterface
{
    /**
     * Returns the function to be executed.
     *
     * @return \Closure
     */
    public function getClosure(): \Closure;

    /**
     * Create an output function and chain it to current function.
     *
     * Send output of the function to another function.
     *
     * @param string $type a function identifier
     * @param array<string, mixed> $options options for the function creation
     */
    public function then(string $type, array $options = []): NodeBuilderInterface;

    /**
     * @param array<int, int|string> $keys
     */
    public function combineKeys(array $keys): NodeBuilderInterface;

    public function deserialize(string $class): NodeBuilderInterface;
    public function callback(callable $param): NodeBuilderInterface;
    public function jsonDecode(): NodeBuilderInterface;
    public function jsonEncode(): NodeBuilderInterface;
}
