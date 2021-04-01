<?php


namespace FunctionalPhp\Tests\ClosureFactory;


use FunctionalPhp\Closure\JsonDecode;
use FunctionalPhp\ClosureFactory\ConstructorFactory;
use FunctionalPhp\Session;
use PHPUnit\Framework\TestCase;

class ConstructorFactoryTest extends TestCase
{
    /**
     * @dataProvider getSupport
     */
    public function testSupport(mixed $type, bool $expected): void
    {
        $factory = new ConstructorFactory();
        $result = $factory->supports($type);
        self::assertEquals($expected, $result);
    }

    /**
     * @return mixed[][]
     */
    public function getSupport(): array
    {
        return [
            [null, false],
            [JsonDecode::class, true],
            [Session::class, false],
        ];
    }

    public function testCreate(): void
    {
        $factory = new ConstructorFactory();
        $result = $factory->create(JsonDecode::class);
        self::assertInstanceOf(\Closure::class, $result);
        $test = iterator_to_array($result('[1, 2]'));
        self::assertEquals([[1, 2]], $test);
    }
}
