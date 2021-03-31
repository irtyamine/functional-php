<?php

namespace FunctionalPhp\Closure;

class JsonEncode
{
    /**
     * @return \Generator<string>
     */
    public function __invoke(mixed $data): \Generator
    {
        yield json_encode($data, JSON_THROW_ON_ERROR);
    }
}
