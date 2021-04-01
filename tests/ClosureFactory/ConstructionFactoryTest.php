<?php

namespace FunctionalPhp\Tests\ClosureFactory;

use FunctionalPhp\ClosureFactory\CallableFactory;
use PHPUnit\Framework\TestCase;

class ConstructionFactoryTest extends TestCase
{
    public function testString(): void
    {
        $factory = new CallableFactory();
        self::assertTrue($factory->supports('strtolower'));
        $closure = $factory->create('strtolower');

        self::assertInstanceOf(\Closure::class, $closure);

        $result = $closure('FOO');
        self::assertSame($result, 'foo');
    }
}
