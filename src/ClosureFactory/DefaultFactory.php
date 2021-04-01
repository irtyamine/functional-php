<?php

namespace FunctionalPhp\ClosureFactory;

class DefaultFactory extends CollectionFactory
{
    public function __construct()
    {
        parent::__construct([
            new CallableFactory(),
            new ConstructorFactory(),
        ]);
    }
}
