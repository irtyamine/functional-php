<?php

namespace FunctionalPhp\ClosureFactory;

class CollectionFactory implements ClosureFactoryInterface
{
    /**
     * @var ClosureFactoryInterface[]
     */
    private array $factories;

    /**
     * @param ClosureFactoryInterface[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    public function supports(string $type, array $options = []): bool
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($type, $options)) {
                return true;
            }
        }

        return false;
    }

    public function create(mixed $type, array $options = []): \Closure
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($type, $options)) {
                return $factory->create($type, $options);
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'The type "%s" is not supported.',
            is_object($type) ? get_class($type) : (
                is_string($type) ? $type : strtolower(gettype($type))
            )
        ));
    }
}
