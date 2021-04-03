<?php

namespace FunctionalPhp;

use FunctionalPhp\Builder\NodeBuilder;
use FunctionalPhp\Builder\NodeBuilderInterface;
use FunctionalPhp\ClosureFactory\ClosureFactoryInterface;
use FunctionalPhp\ClosureFactory\DefaultFactory;
use FunctionalPhp\Driver\DefaultDriver;
use FunctionalPhp\Driver\DriverInterface;
use FunctionalPhp\Graph\Graph;
use FunctionalPhp\Graph\Runner;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Session implements SessionInterface
{
    private Graph $graph;
    private DriverInterface $driver;
    private ClosureFactoryInterface $closureFactory;
    private LoggerInterface $logger;

    public function __construct(DriverInterface $driver = null, ClosureFactoryInterface $closureFactory = null, ?LoggerInterface $logger = null)
    {
        $this->graph = new Graph(logger: $logger);
        $this->driver = $driver ?? new DefaultDriver();
        $this->closureFactory = $closureFactory ?? new DefaultFactory();
        $this->logger = $logger ?? new NullLogger();
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
        $this->logger->info('Start running the graph');
        $loader = new Runner($this->graph, $this->driver);
        $loader->run();
        $this->logger->info('Finished running the graph');
    }
}
