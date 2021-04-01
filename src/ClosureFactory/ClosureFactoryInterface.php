<?php

namespace FunctionalPhp\ClosureFactory;

interface ClosureFactoryInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function supports(string $type, array $options = []): bool;

    /**
     * @param array<string, mixed> $options
     */
    public function create(mixed $type, array $options = []): \Closure;
}
