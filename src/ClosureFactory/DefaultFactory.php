<?php

namespace FunctionalPhp\ClosureFactory;

use FunctionalPhp\Metadata\MetadataFactoryInterface;

class DefaultFactory implements ClosureFactoryInterface
{
    private ClosureFactoryInterface $factory;

    public function __construct(?MetadataFactoryInterface $metadataFactory = null)
    {
        $factory = new CollectionFactory([
            new CallableFactory(),
            new ConstructorFactory(),
        ]);

        if ($metadataFactory) {
            $factory = new DebugFactory($factory, $metadataFactory);
        }

        $this->factory = $factory;
    }

    public function supports(string $type, array $options = []): bool
    {
        return $this->factory->supports($type, $options);
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        return $this->factory->create($type, $options);
    }
}
