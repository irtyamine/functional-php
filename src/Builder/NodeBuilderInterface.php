<?php

namespace FunctionalPhp\Builder;

/**
 * Interface used to describe a functional program.
 */
interface NodeBuilderInterface
{
    /**
     * Create an output function and chain it to current function.
     *
     * Send output of the function to another function.
     *
     * @param mixed $type a function identifier
     * @param array<string, mixed> $options options for the function creation
     */
    public function then(mixed $type, array $options = []): NodeBuilderInterface;
}
