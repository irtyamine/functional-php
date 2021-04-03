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
     * Execution data.
     *
     * @var \SplObjectStorage<\Closure, mixed>
     */
    private \SplObjectStorage $closureStats;

    /**
     * List of running generators and their targets.
     *
     * @var \SplObjectStorage<\Generator, \Closure[]>
     */
    private \SplObjectStorage $generators;

    /**
     * Execution data.
     *
     * @var \SplObjectStorage<\Generator, \Closure>
     */
    private \SplObjectStorage $closureByGenerator;

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
        $this->closureStats = new \SplObjectStorage();
        $this->generators = new \SplObjectStorage();
        $this->closureByGenerator = new \SplObjectStorage();
    }

    public function run(?callable $monitor = null): void
    {
        $this->graph->validate();
        $this->logger->info('Loading the graph');
        if ($monitor) {
            $this->driver->interval(1, function () use ($monitor): bool {
                $data = [
                    'push_stack' => count($this->pushStack),
                    'generators' => count($this->generators),
                    'closures' => [],
                ];

                foreach ($this->closureStats as $closure) {
                    $id = substr(md5(spl_object_hash($closure)), 0, 8);
                    $data['closures'][] = [
                        'id' => $id,
                        'running' => $this->closureStats[$closure]->running,
                        'received' => $this->closureStats[$closure]->received,
                        'sent' => $this->closureStats[$closure]->sent,
                    ];
                }

                $monitor($data);

                return !(empty($this->pushStack) && count($this->generators) === 0);
            });
        }
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
        $i = 0;
        while ($i < 100 && $this->doTick()) {
            $i++;
        }

        if ($i === 100) {
            $this->driver->future([$this, 'tick']);
        }
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
            $this->closureStats[$closure] = (object)[
                'running' => 0,
                'received' => 0,
                'sent' => 0,
            ];
        }

        $this->driver->future([$this, 'tick']);
    }

    private function doTick(): bool
    {
        if (!empty($this->pushStack)) {
            [$closure, $args] = array_shift($this->pushStack);
            /** @var \Closure $closure */
            $args = is_array($args) ? $args : [$args];
            /** @var \Generator<mixed> $generator */
            $generator = $closure(...$args);
            $this->closureStats[$closure]->received++;
            if (!empty($this->targets[$closure])) {
                $this->closureStats[$closure]->running++;
                $this->closureByGenerator[$generator] = $closure;
                $this->generators[$generator] = $this->targets[$closure];
            } else {
                // Complete output generators
                while ($generator->valid()) {
                    $generator->next();
                }
            }
        } elseif (count($this->generators) === 0) {
            return false;
        }

        if (!$this->generators->valid()) {
            $this->generators->rewind();
        }

        if (!$this->generators->valid()) {
            return false;
        }

        /** @var \Generator<mixed> $generator */
        $generator = $this->generators->current();
        $closure = $this->closureByGenerator[$generator];

        if (!$generator->valid()) {
            $this->closureStats[$closure]->running--;
            unset(
                $this->generators[$generator],
                $this->closureByGenerator[$generator]
            );
        } else {
            $value = $generator->current();
            foreach ($this->generators->getInfo() as $target) {
                $this->pushStack[] = [$target, $value];
            }
            $this->closureStats[$closure]->sent++;
            $generator->next();
            $this->generators->next();
        }

        return true;
    }
}
