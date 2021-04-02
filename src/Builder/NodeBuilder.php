<?php

namespace FunctionalPhp\Builder;

use FunctionalPhp\ClosureFactory\ClosureFactoryInterface;
use FunctionalPhp\Graph\Graph;

class NodeBuilder implements NodeBuilderInterface
{
    private Graph $graph;
    private ClosureFactoryInterface $closureFactory;
    private \Closure $closure;

    public function __construct(Graph $graph, ClosureFactoryInterface $closureFactory, \Closure $closure)
    {
        $this->graph = $graph;
        $this->closureFactory = $closureFactory;
        $this->closure = $closure;
    }

    public function then(mixed $type, array $options = []): NodeBuilderInterface
    {
        $new = $this->closureFactory->create($type, $options);
        $this->graph->add($new);
        $this->graph->chain($this->closure, $new);

        return new self($this->graph, $this->closureFactory, $new);
    }
}
