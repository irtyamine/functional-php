<?php

namespace FunctionalPhp\Bridge\Symfony;

use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    private SerializerInterface $serializer;
    private string $format;

    /**
     * @var array<mixed, string>
     */
    private array $context;

    /**
     * @param array<mixed, string> $context
     */
    public function __construct(SerializerInterface $serializer, string $format, array $context)
    {
        $this->serializer = $serializer;
        $this->format = $format;
        $this->context = $context;
    }

    /**
     * @return \Generator<string>
     */
    public function __invoke(mixed $data): \Generator
    {
        yield $this->serializer->serialize($data, $this->format, $this->context);
    }
}
