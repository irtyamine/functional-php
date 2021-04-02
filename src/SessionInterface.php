<?php

namespace FunctionalPhp;

use FunctionalPhp\Builder\NodeBuilderInterface;

interface SessionInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function from(string $type, array $options = []): NodeBuilderInterface;
}
