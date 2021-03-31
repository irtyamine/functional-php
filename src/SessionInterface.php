<?php

namespace FunctionalPhp;

interface SessionInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function from(string $type, array $options = []): NodeBuilderInterface;

    public function chain(\Closure $from, \Closure $to): void;
}
