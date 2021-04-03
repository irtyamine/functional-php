<?php

namespace FunctionalPhp\Graph;

use FunctionalPhp\Driver\DriverInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Runner
{
    /**
     * The graph being run.
     */
    private Graph $graph;

    /**
     * Driver used for running the graph.
     */
    private DriverInterface $driver;

    /**
     * Logs information about the run.
     */
    private LoggerInterface $logger;

    /**
     * Cache of graph's targets.
     *
     * @var \SplObjectStorage<\Closure, \Closure[]>
     */
    private \SplObjectStorage $targets;

    /**
     * List of running generators and their targets.
     *
     * @var \SplObjectStorage<\Generator, \Closure[]>
     */
    private \SplObjectStorage $runningGenerators;

    /**
     * List of values to be pushed to graph's closure.
     *
     * @var array{0: \Closure, 1: mixed}[]
     */
    private array $pushStack = [];

    public function __construct(Graph $graph, DriverInterface $driver, ?LoggerInterface $logger = null)
    {
        $this->graph = $graph;
        $this->driver = $driver;
        $this->logger = $logger ?? new NullLogger();
        $this->targets = new \SplObjectStorage();
        $this->runningGenerators = new \SplObjectStorage();
    }

    public function run(): void
    {
        $this->graph->validate();
        $this->logger->info('Loading the graph');
        $this->load();
        $this->logger->info('Start running the graph');
        $this->driver->run();
        $this->logger->info('Finished running the graph');
    }

    /**
     * This function is the critical code that runs a graph.
     */
    public function tick(): void
    {
        if (!empty($this->pushStack)) {
            [$closure, $args] = array_shift($this->pushStack);
            /** @var \Closure $closure */
            $args = is_array($args) ? $args : [$args];
            /** @var \Generator<mixed> $generator */
            $generator = $closure(...$args);
            $generator->current();
            if (!empty($this->targets[$closure])) {
                $this->runningGenerators[$generator] = $this->targets[$closure];
            }
        } elseif (empty($this->runningGenerators)) {
            return;
        }

        if (!$this->runningGenerators->valid()) {
            $this->runningGenerators->rewind();
        }

        if (!$this->runningGenerators->valid()) {
            return;
        }

        /** @var \Generator<mixed> $generator */
        $generator = $this->runningGenerators->current();

        if (!$generator->valid()) {
            unset($this->runningGenerators[$generator]);
        } else {
            $value = $generator->current();
            foreach ($this->runningGenerators->getInfo() as $target) {
                $this->pushStack[] = [$target, $value];
            }
            $generator->next();
            $this->runningGenerators->next();
        }

        $this->driver->future([$this, 'tick']);
    }

    private function load(): void
    {
        $this->targets = new \SplObjectStorage();

        foreach ($this->graph->getClosures() as $closure) {
            if ($this->graph->isSource($closure)) {
                // Starts a source
                $this->pushStack[] = [$closure, []];
            }

            $this->targets[$closure] = iterator_to_array($this->graph->getTargets($closure));
        }

        $this->driver->future([$this, 'tick']);
    }
}
