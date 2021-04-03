<?php

namespace FunctionalPhp\ClosureFactory;

use FunctionalPhp\Metadata\MetadataFactoryInterface;

class DebugFactory implements ClosureFactoryInterface
{
    private ClosureFactoryInterface $closureFactory;
    private MetadataFactoryInterface $metadataFactory;

    public function __construct(ClosureFactoryInterface $closureFactory, MetadataFactoryInterface $metadataFactory)
    {
        $this->closureFactory = $closureFactory;
        $this->metadataFactory = $metadataFactory;
    }

    public function supports(string $type, array $options = []): bool
    {
        return $this->closureFactory->supports($type, $options);
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        $closure = $this->closureFactory->create($type, $options);
        $metadata = $this->metadataFactory->getMetadataFor($closure);
        if ($metadata->getReturnType() !== 'Generator') {
            throw new \RuntimeException(sprintf('This type does not return a "Generator". It returns a "%s".', $metadata->getReturnType()));
        }

        return $closure;
    }
}
