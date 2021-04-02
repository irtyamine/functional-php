<?php

namespace FunctionalPhp\Metadata;

class ClosureMetadata
{
    /**
     * @var ClosureParameter[]
     */
    private array $parameters;

    /**
     * @param ClosureParameter[] $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function hasParameters(): bool
    {
        return count($this->parameters) > 0;
    }
}
