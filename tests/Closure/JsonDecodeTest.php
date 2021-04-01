<?php

namespace FunctionalPhp\Tests\Closure;

use FunctionalPhp\Closure\JsonDecode;
use PHPUnit\Framework\TestCase;

class JsonDecodeTest extends TestCase
{
    public function testSingle(): void
    {
        $empty = new JsonDecode();

        $result = iterator_to_array($empty('[1, 2]'));
        self::assertSame([[1, 2]], $result);
    }
}
