<?php

namespace FunctionalPhp\Tests\Closure;

use FunctionalPhp\Closure\ArrayCombine;
use PHPUnit\Framework\TestCase;

class ArrayCombineTest extends TestCase
{
    public function testSingle(): void
    {
        $empty = new ArrayCombine(['foo', 'bar']);
        $raw = [1, 2];
        $result = iterator_to_array($empty($raw));
        self::assertSame([['foo' => 1, 'bar' => 2]], $result);
    }
}
