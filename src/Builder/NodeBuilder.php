<?php

namespace FunctionalPhp\Builder;

use FunctionalPhp\Bridge\Symfony\Deserializer;
use FunctionalPhp\Closure\ArrayCombine;
use FunctionalPhp\Closure\Callback;
use FunctionalPhp\Closure\JsonDecode;
use FunctionalPhp\Closure\JsonEncode;
use FunctionalPhp\SessionInterface;

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

    public function then(string $type, array $options = []): NodeBuilderInterface
    {
        $node = $this->session->from($type, $options);
        $this->session->chain($this->closure, $node->getClosure());

        return $node;
    }

    public function combineKeys(array $keys): NodeBuilderInterface
    {
        return $this->then(ArrayCombine::class, ['keys' => $keys]);
    }

    public function deserialize(string $class): NodeBuilderInterface
    {
        return $this->then(Deserializer::class, []);
    }

    public function callback(callable $param): NodeBuilderInterface
    {
        return $this->then(Callback::class, ['callback' => $param]);
    }

    public function jsonDecode(): NodeBuilderInterface
    {
        return $this->then(JsonDecode::class);
    }

    public function jsonEncode(): NodeBuilderInterface
    {
        return $this->then(JsonEncode::class);
    }
}
