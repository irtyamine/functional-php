<?php

namespace FunctionalPhp\Tests\ClosureFactory;

use FunctionalPhp\ClosureFactory\ClosureFactoryInterface;
use FunctionalPhp\ClosureFactory\CollectionFactory;
use PHPUnit\Framework\TestCase;

class CollectionFactoryTest extends TestCase
{
    public function testSupport(): void
    {
        $first = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $second = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $first->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(true);
        $second->expects(self::never())->method('supports')->with('foo', ['bar' => true])->willReturn(false);

        $factory = new CollectionFactory([$first, $second]);
        self::assertTrue($factory->supports('foo', ['bar' => true]));
    }


    public function testSupportSecond(): void
    {
        $first = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $second = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $first->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);
        $second->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(true);

        $factory = new CollectionFactory([$first, $second]);
        self::assertTrue($factory->supports('foo', ['bar' => true]));
    }

    public function testNotSupport(): void
    {
        $first = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $second = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $first->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);
        $second->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);

        $factory = new CollectionFactory([$first, $second]);
        self::assertFalse($factory->supports('foo', ['bar' => true]));
    }

    public function testCreate(): void
    {
        $result = \Closure::fromCallable('explode');
        $first = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $second = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $first->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);
        $first->expects(self::never())->method('create');
        $second->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(true);
        $second->expects(self::once())->method('create')->with('foo', ['bar' => true])->willReturn($result);

        $factory = new CollectionFactory([$first, $second]);
        self::assertSame($result, $factory->create('foo', ['bar' => true]));
    }

    public function testCreateUnsupported(): void
    {
        $first = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $second = $this->getMockBuilder(ClosureFactoryInterface::class)->getMock();
        $first->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);
        $second->expects(self::once())->method('supports')->with('foo', ['bar' => true])->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $factory = new CollectionFactory([$first, $second]);
        $factory->create('foo', ['bar' => true]);
    }
}
