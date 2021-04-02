<?php

namespace FunctionalPhp;

use FunctionalPhp\Builder\NodeBuilder;
use FunctionalPhp\Builder\NodeBuilderInterface;
use FunctionalPhp\ClosureFactory\ClosureFactoryInterface;
use FunctionalPhp\ClosureFactory\DefaultFactory;
use FunctionalPhp\Driver\DefaultDriver;
use FunctionalPhp\Driver\DriverInterface;
use FunctionalPhp\Graph\Graph;
use FunctionalPhp\Graph\GraphLoader;

class Session implements SessionInterface
{
    private Graph $graph;
    private DriverInterface $driver;
    private ClosureFactoryInterface $closureFactory;

    public function __construct(DriverInterface $driver = null, ClosureFactoryInterface $closureFactory = null)
    {
        $this->graph = new Graph();
        $this->driver = $driver ?? new DefaultDriver();
        $this->closureFactory = $closureFactory ?? new DefaultFactory();
    }

    public function from(string $type, array $options = []): NodeBuilderInterface
    {
        $closure = $this->closureFactory->create($type, $options);
        $this->graph->add($closure);

        return new NodeBuilder($this->graph, $this->closureFactory, $closure);
    }

    public function run(): void
    {
        $this->graph->validate();
        $loader = new GraphLoader($this->graph, $this->driver);
        $loader->load();
        $this->driver->run();
    }
}
