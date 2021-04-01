<?php

namespace FunctionalPhp\Tests\Closure;

use FunctionalPhp\Closure\Callback;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    public function testSingle(): void
    {
        $empty = new Callback(function (int $x) {
            yield $x;
            yield $x + 1;
        });

        $result = iterator_to_array($empty(1));
        self::assertSame([1, 2], $result);
    }
}
