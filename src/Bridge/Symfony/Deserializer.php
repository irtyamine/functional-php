<?php

namespace FunctionalPhp\Bridge\Symfony;

use Symfony\Component\Serializer\SerializerInterface;

class Deserializer
{
    private SerializerInterface $serializer;
    private string $type;
    private string $format;


    /**
     * @var array<mixed, string>
     */
    private array $context;

    /**
     * @param array<mixed, string> $context
     */
    public function __construct(SerializerInterface $serializer, string $type, string $format, array $context)
    {
        $this->serializer = $serializer;
        $this->type = $type;
        $this->format = $format;
        $this->context = $context;
    }

    /**
     * @return \Generator<string>
     */
    public function __invoke(mixed $data): \Generator
    {
        yield $this->serializer->deserialize($data, $this->type, $this->format, $this->context);
    }
}
