<?php

namespace FunctionalPhp;

use FunctionalPhp\Bridge\ReactPHP\Driver\ReactPHPDriver;
use FunctionalPhp\Builder\NodeBuilder;
use FunctionalPhp\Builder\NodeBuilderInterface;
use FunctionalPhp\ClosureFactory\ClosureFactoryInterface;
use FunctionalPhp\ClosureFactory\DefaultFactory;
use FunctionalPhp\Driver\DriverInterface;

class Session implements SessionInterface
{
    private DriverInterface $driver;
    private ClosureFactoryInterface $closureFactory;
    private Registry $registry;
    private ?\DateTimeImmutable $startedAt = null;
    private ?\DateTimeImmutable $finishedAt = null;
    private ?\Throwable $error = null;

    public function __construct(DriverInterface $driver = null, ClosureFactoryInterface $closureFactory = null)
    {
        $this->driver = $driver ?? new ReactPHPDriver();
        $this->closureFactory = $closureFactory ?? new DefaultFactory();
        $this->registry = new Registry();
    }

    public function from(string $type, array $options = []): NodeBuilderInterface
    {
        $closure = $this->closureFactory->create($type, $options);
        $this->registry->register($closure);

        return new NodeBuilder($this, $closure);
    }

    public function chain(\Closure $from, \Closure $to): void
    {
        $this->registry->edge($from, $to);
    }

    public function run(): void
    {
        if (null !== $this->startedAt) {
            throw new \LogicException('Session is already started.');
        }

        $this->startedAt = new \DateTimeImmutable();

        try {
            $this->driver->start();
            foreach ($this->registry->getClosures() as $closure) {
                if ($this->registry->isInputOutput($closure)[0]) {

                }
            }
            $this->driver->run();
            dump("finish running");
        } catch (\Throwable $error) {
            $this->error = $error;
            throw $error;
        } finally {
            $this->stop();
        }
    }

    public function stop(): void
    {
        if (null === $this->startedAt) {
            throw new \LogicException('Session is not started yet.');
        }

        if (null !== $this->finishedAt) {
            throw new \LogicException('Session has already been stopped.');
        }

        $this->finishedAt = new \DateTimeImmutable();
        $this->driver->stop();
        $this->driver->run();
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * @return \Throwable|null
     */
    public function getError(): ?\Throwable
    {
        return $this->error;
    }
}
