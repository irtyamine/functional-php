<?php

namespace FunctionalPhp\Tests\ClosureFactory;

use FunctionalPhp\ClosureFactory\CallableFactory;
use PHPUnit\Framework\TestCase;

class CallableFactoryTest extends TestCase
{
    public function testString(): void
    {
        $factory = new CallableFactory();
        self::assertTrue($factory->supports('strtolower'));
        $closure = $factory->create('strtolower');

        self::assertInstanceOf(\Closure::class, $closure);

        $result = iterator_to_array($closure('FOO'));
        self::assertSame(['foo'], $result);
    }
}
