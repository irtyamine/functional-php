<?php

namespace FunctionalPhp\Graph;

use FunctionalPhp\Metadata\ClosureMetadata;
use FunctionalPhp\Metadata\MetadataFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Graph
{
    private Registry $registry;
    private MetadataFactoryInterface $metadataFactory;
    private LoggerInterface $logger;

    public function __construct(MetadataFactoryInterface $metadataFactory, ?LoggerInterface $logger = null)
    {
        $this->registry = new Registry();
        $this->metadataFactory = $metadataFactory;
        $this->logger = $logger ?? new NullLogger();
    }

    public function add(\Closure $closure): void
    {
        $this->logger->debug('Adding a new closure to the graph');

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
        $this->logger->debug('Chaining two closures into the graph');

        /** @var \SplObjectStorage<\Closure, mixed> $fromStorage */
        $fromStorage = $this->registry->getInformation($from, 'to');
        $fromStorage[$to] = $options;
        $toStorage = $this->registry->getInformation($to, 'from');
        $toStorage[$from] = $options;
    }

    public function validate(): void
    {
        foreach ($this->getClosures() as $closure) {
            $this->validateClosure($closure);
        }
    }

    /**
     * @return \Generator<\Closure>
     */
    public function getClosures(): \Generator
    {
        yield from $this->registry->getClosures();
    }

    public function getMetadataFor(\Closure $closure): ClosureMetadata
    {
        return $this->metadataFactory->getMetadataFor($closure);
    }

    private function validateClosure(\Closure $closure): void
    {
        $metadata = $this->metadataFactory->getMetadataFor($closure);
        if ($this->isSource($closure) && $metadata->hasParameters()) {
            throw new \RuntimeException('A source closure cannot have input parameters');
        }
    }

    public function isSource(\Closure $closure): bool
    {
        return count($this->registry->getInformation($closure, 'from')) === 0;
    }

    public function isEnd(\Closure $closure): bool
    {
        return count($this->registry->getInformation($closure, 'to')) === 0;
    }

    /**
     * @return \Generator<\Closure>
     */
    public function getTargets(\Closure $closure): \Generator
    {
        /** @var \SplObjectStorage<\Closure, mixed> $to */
        $to = $this->registry->getInformation($closure, 'to');
        $to->rewind();
        for (;$to->valid();) {
            yield $to->current();
            $to->next();
        }
    }

    /**
     * @return \Generator<mixed>
     */
    public function getEdges(): \Generator
    {
        foreach ($this->getClosures() as $closure) {
            $to = $this->registry->getInformation($closure, 'to');
            $to->rewind();
            for (;$to->valid();) {
                yield [$closure, $to->current(), $to->getInfo()];
                $to->next();
            }
        }
    }
}
