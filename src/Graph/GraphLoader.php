<?php

namespace FunctionalPhp\Graph;

use FunctionalPhp\Driver\DriverInterface;

class GraphLoader
{
    private Graph $graph;
    private DriverInterface $driver;

    public function __construct(Graph $graph, DriverInterface $driver)
    {
        $this->graph = $graph;
        $this->driver = $driver;
    }

    public function load(): void
    {
        foreach ($this->graph->getClosures() as $closure) {
            $this->loadClosure($closure);
        }
    }

    private function loadClosure(\Closure $closure): void
    {
        if ($this->graph->isSource($closure)) {
            $this->loadSource($closure);
        }
    }

    private function loadSource(\Closure $closure): void
    {
        $generator = null;
        $this->driver->interval(0, function (callable $stop) use ($closure, &$generator): void {
            if (null === $generator) {
                $generator = $closure();
            }

            if (!$generator->valid()) {
                $stop();

                return;
            }

            $single = $generator->current();
            $this->deliver($closure, $single);
            $generator->next();
        });
    }

    private function deliver(\Closure $closure, mixed $value): void
    {
        $this->driver->future(function () use ($closure, $value) {
            foreach ($this->graph->getTargets($closure) as $target) {
                $this->driver->future(function () use ($target, $value) {
                    $result = $target($value);
                    if (!$result instanceof \Generator) {
                        $this->deliver($target, $result);

                        return;
                    }

                    $this->driver->future(function () use ($target, $result) {
                        foreach ($result as $one) {
                            $this->driver->future(function () use ($target, $one) {
                                $this->deliver($target, $one);
                            });
                        }
                    });
                });
            }
        });
    }
}
