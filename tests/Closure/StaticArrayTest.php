<?php

namespace FunctionalPhp\Tests\Closure;

use FunctionalPhp\Closure\StaticArray;
use PHPUnit\Framework\TestCase;

class StaticArrayTest extends TestCase
{
    public function testEmpty(): void
    {
        $empty = new StaticArray([]);
        self::assertIsCallable($empty);
        self::assertEmpty(iterator_to_array($empty()));
    }

    public function testSingle(): void
    {
        $empty = new StaticArray([1]);
        $results = iterator_to_array($empty());
        self::assertCount(1, $results);
        self::assertArrayHasKey(0, $results);
    }

    public function testMultiple(): void
    {
        $empty = new StaticArray([1, 2, 3]);
        $results = iterator_to_array($empty());
        self::assertSame([1, 2, 3], $results);
    }

    public function testKey(): void
    {
        $empty = new StaticArray(['foo' => 'bar']);
        $results = iterator_to_array($empty());
        self::assertSame(['foo' => 'bar'], $results);
    }
}
