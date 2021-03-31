<?php

namespace FunctionalPhp;

class Registry
{
    /**
     * @var \SplObjectStorage<\Closure, array{targets: \SplObjectStorage<\Closure, mixed>}>
     */
    private \SplObjectStorage $config;

    public function __construct()
    {
        $this->config = new \SplObjectStorage();
    }

    public function register(\Closure $closure): void
    {
        /** @var \SplObjectStorage<\Closure, mixed> $to */
        $to = new \SplObjectStorage();

        $this->config[$closure] = [
            'targets' => $to,
        ];
    }

    /**
     * @param array<string, mixed> $config
     */
    public function edge(\Closure $from, \Closure $to, array $config = []): void
    {
        if (!isset($this->config[$from])) {
            $this->register($from);
        }
        if (!isset($this->config[$to])) {
            $this->register($to);
        }

        $configFrom = $this->config[$from];
        $configFrom['targets'][$to] = $config;
        $this->config[$from] = $configFrom;
    }

    /**
     * @return iterable<\Closure>
     */
    public function getClosures(): iterable
    {
        foreach ($this->config as $config) {
            yield $this->config->current();
        }
    }
}
