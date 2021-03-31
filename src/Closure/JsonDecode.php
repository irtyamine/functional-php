<?php

namespace FunctionalPhp\Closure;

class JsonDecode
{
    /**
     * @return \Generator<mixed>
     */
    public function __invoke(string $data): \Generator
    {
        yield json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }
}
