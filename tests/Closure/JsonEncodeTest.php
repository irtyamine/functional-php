<?php

namespace FunctionalPhp\Tests\Closure;

use FunctionalPhp\Closure\JsonEncode;
use PHPUnit\Framework\TestCase;

class JsonEncodeTest extends TestCase
{
    public function testSingle(): void
    {
        $empty = new JsonEncode();

        $result = iterator_to_array($empty([1, 2]));
        self::assertSame(['[1,2]'], $result);
    }
}
