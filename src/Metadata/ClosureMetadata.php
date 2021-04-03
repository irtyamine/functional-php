<?php

namespace FunctionalPhp\Metadata;

class ClosureMetadata
{
    /**
     * @var ClosureParameter[]
     */
    private array $parameters;
    private string $returnType;

    /**
     * @param ClosureParameter[] $parameters
     */
    public function __construct(array $parameters, string $returnType)
    {
        $this->parameters = $parameters;
        $this->returnType = $returnType;
    }

    public function hasParameters(): bool
    {
        return count($this->parameters) > 0;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }
}
