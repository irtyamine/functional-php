<?php

namespace FunctionalPhp;

class Registry
{
    /**
     * @var \SplObjectStorage<\Closure, array{from: \SplObjectStorage<\Closure, mixed>, to: \SplObjectStorage<\Closure, mixed>}>
     */
    private \SplObjectStorage $config;

    public function __construct()
    {
        $this->config = new \SplObjectStorage();
    }

    public function register(\Closure $closure): void
    {
        /** @var \SplObjectStorage<\Closure, mixed> $from */
        $from = new \SplObjectStorage();
        /** @var \SplObjectStorage<\Closure, mixed> $to */
        $to = new \SplObjectStorage();

        $this->config[$closure] = [
            'from' => $from,
            'to' => $to,
        ];
    }

    public function edge(\Closure $from, \Closure $to): void
    {
        if (!isset($this->config[$from])) {
            $this->register($from);
        }
        if (!isset($this->config[$to])) {
            $this->register($to);
        }

        $configFrom = $this->config[$from];
        $configTo = $this->config[$to];
        $configFrom['to'][$to] = $to;
        $configTo['from'][$from] = $from;
        $this->config[$from] = $configFrom;
        $this->config[$to] = $configTo;
    }

    /**
     * @return iterable<\Closure>
     */
    public function getClosures(): iterable
    {
        $this->config->rewind();
        for (;$this->config->valid();) {
            yield $this->config->current();
        }
    }

    /**
     * @return bool[]
     */
    public function isInputOutput(\Closure $closure): array
    {
        if (!isset($this->config[$closure])) {
            throw new \InvalidArgumentException('The provided Closure is not in the registry.');
        }

        $from = $this->config[$closure]['from'];
        $to = $this->config[$closure]['to'];

        return [count($from) === 0, count($to) === 0];
    }
}
