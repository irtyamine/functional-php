<?php

namespace FunctionalPhp\Graph;

/**
 * Holds Closure collection and information about them.
 */
class Registry
{
    /** @var \SplObjectStorage<\Closure, array<string, mixed>> */
    private \SplObjectStorage $entries;

    public function __construct()
    {
        $this->entries = new \SplObjectStorage();
    }

    /**
     * @param \Closure $closure
     * @param array<string, mixed> $information
     */
    public function register(\Closure $closure, array $information = []): void
    {
        if (isset($this->entries[$closure])) {
            throw new \InvalidArgumentException('Closure is already registered.');
        }

        $this->entries[$closure] = $information;
    }

    public function getAllInformation(\Closure $closure): mixed
    {
        if (!isset($this->entries[$closure])) {
            throw new \InvalidArgumentException('Closure not in registry.');
        }

        return $this->entries[$closure];
    }

    public function getInformation(\Closure $closure, string $name, mixed $default = null): mixed
    {
        if (!isset($this->entries[$closure])) {
            throw new \InvalidArgumentException('Closure not in registry.');
        }

        return $this->entries[$closure][$name] ?? $default;
    }

    public function setInformation(\Closure $closure, string $name, mixed $value): void
    {
        if (!isset($this->entries[$closure])) {
            throw new \InvalidArgumentException('Closure not in registry.');
        }

        $this->entries[$closure][$name] = $value;
    }

    /**
     * @return \Generator<\Closure>
     */
    public function getClosures(): \Generator
    {
        $this->entries->rewind();
        for (;$this->entries->valid();) {
            yield $this->entries->current();
            $this->entries->next();
        }
    }
}
