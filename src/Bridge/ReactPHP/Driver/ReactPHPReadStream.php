<?php


namespace FunctionalPhp\Bridge\ReactPHP\Driver;

use Evenement\EventEmitter;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;

class ReactPHPReadStream extends EventEmitter
{
    private LoopInterface $loop;
    /** @var \Generator<mixed, mixed>|null  */
    private ?\Generator $generator = null;

    public function __construct(LoopInterface $loop, \Generator $generator)
    {
        $this->loop = $loop;
        $this->generator = $generator;
    }

    public function start(): void
    {
        foreach ($this->generator as $n) {
            dump($n);
        }
        dd('inter');
        $this->loop->addPeriodicTimer(0, function (TimerInterface $timer) {
            $data = $this->current->current();
            if (!$this->current?->valid()) {
                $this->emit('close');
                $this->loop->cancelTimer($timer);

                return;
            }

            $this->emit('data', [$data]);
        });
    }
}
