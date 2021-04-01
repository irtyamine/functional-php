<?php

namespace FunctionalPhp\Graph;

class Graph
{
    private Registry $registry;

    public function __construct()
    {
        $this->registry = new Registry();
    }

    public function add(\Closure $closure): void
    {
        $this->registry->register($closure, [
            'from' => new \SplObjectStorage(),
            'to' => new \SplObjectStorage(),
        ]);
    }

    /**
     * @param \Closure $from
     * @param \Closure $to
     * @param array<string, mixed> $options
     */
    public function chain(\Closure $from, \Closure $to, array $options = []): void
    {
        /** @var \SplObjectStorage<\Closure, mixed> $fromStorage */
        $fromStorage = $this->registry->getInformation($from, 'to');
        $fromStorage[$to] = $options;
        $toStorage = $this->registry->getInformation($to, 'from');
        $toStorage[$from] = $options;
    }
}
