<?php

namespace FunctionalPhp;

use FunctionalPhp\Bridge\Symfony\Deserializer;
use FunctionalPhp\Closure\ArrayCombine;
use FunctionalPhp\Closure\Callback;
use FunctionalPhp\Closure\JsonDecode;
use FunctionalPhp\Closure\JsonEncode;

class NodeBuilder implements NodeBuilderInterface
{
    private SessionInterface $session;
    private \Closure $closure;

    public function __construct(SessionInterface $session, \Closure $closure)
    {
        $this->session = $session;
        $this->closure = $closure;
    }

    public function getClosure(): \Closure
    {
        return $this->closure;
    }

    public function write(string $type, array $options = []): NodeBuilderInterface
    {
        $node = $this->session->from($type, $options);
        $this->session->chain($this->closure, $node->getClosure());

        return $node;
    }

    public function combineKeys(array $keys): NodeBuilderInterface
    {
        return $this->write(
            ArrayCombine::class,
            ['keys' => $keys],
        );
    }

    public function deserialize(string $class): NodeBuilderInterface
    {
        return $this->write(
            Deserializer::class,
            [],
        );
    }

    public function callback(callable $param): NodeBuilderInterface
    {
        return $this->write(
            Callback::class,
            ['callback' => $param]
        );
    }

    public function jsonDecode(): NodeBuilderInterface
    {
        return $this->write(JsonDecode::class);
    }

    public function jsonEncode(): NodeBuilderInterface
    {
        return $this->write(JsonEncode::class);
    }
}
