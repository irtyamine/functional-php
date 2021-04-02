<?php

namespace FunctionalPhp\Metadata;

class ClosureParameter
{
    public string $name;
    public string $type;
    public bool $required;
    public mixed $default;

    public function __construct(string $name, string $type, bool $required, mixed $default)
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
    }
}
