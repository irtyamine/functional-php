<?php

namespace FunctionalPhp\Graph;

use FunctionalPhp\Metadata\ClosureMetadata;
use FunctionalPhp\Metadata\DefaultMetadataFactory;
use FunctionalPhp\Metadata\MetadataFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Graph
{
    private Registry $registry;
    private MetadataFactoryInterface $metadataFactory;
    private LoggerInterface $logger;

    public function __construct(?MetadataFactoryInterface $metadataFactory = null, ?LoggerInterface $logger = null)
    {
        $this->registry = new Registry();
        $this->metadataFactory = $metadataFactory ?? new DefaultMetadataFactory();
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
        $this->logger->debug('Validating the graph');
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

    /**
     * @return \Generator<\Closure>
     */
    public function getSources(): \Generator
    {
        foreach ($this->getClosures() as $closure) {
            if ($this->isSource($closure)) {
                yield $closure;
            }
        }
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
}
