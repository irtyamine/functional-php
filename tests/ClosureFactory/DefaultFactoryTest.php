<?php

namespace FunctionalPhp\Tests\ClosureFactory;

use FunctionalPhp\ClosureFactory\DefaultFactory;
use PHPUnit\Framework\TestCase;

class DefaultFactoryTest extends TestCase
{
    public function testCreateStringCallback(): void
    {
        $factory = new DefaultFactory();

        // string callback
        $result = iterator_to_array($factory->create('implode')(', ', [2, 3]));
        self::assertSame(['2, 3'], $result);
    }

        public function testCreateClosure(): void
    {
        $factory = new DefaultFactory();

        // Closure
        $result = iterator_to_array($factory->create(function ($left, $right) {
            return $left + $right;
        })(1, 3));

        self::assertSame([4], $result);
    }
}
