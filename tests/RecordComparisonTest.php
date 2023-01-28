<?php

declare(strict_types=1);

namespace Hereldar\Records\Tests;

use Generator;
use Hereldar\Records\Record;

/** @internal */
final readonly class Person1 extends Record
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}

/** @internal */
final readonly class Person2 extends Record
{
    public function __construct(
        public string $name,
        public int $id,
    ) {
    }
}

/** @internal */
final readonly class Person3 extends Record
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
    }
}

final class RecordComparisonTest extends TestCase
{
    private static Record $record1;
    private static object $object1;
    private static array $array1;

    private static Record $record2;
    private static object $object2;
    private static array $array2;

    private static Record $record3;
    private static object $object3;
    private static array $array3;

    private static Record $record4;
    private static object $object4;
    private static array $array4;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$record1 = new Person1(id: 1, name: 'Bilbo Bolsón');
        self::$record2 = new Person2(name: 'Bilbo Bolsón', id: 1);
        self::$record3 = new Person3(name: 'Bilbo Bolsón', id: '1');
        self::$record4 = new Person1(id: 2, name: 'Frodo Bolsón');

        self::$array1 = ['id' => 1, 'name' => 'Bilbo Bolsón'];
        self::$array2 = ['name' => 'Bilbo Bolsón', 'id' => 1];
        self::$array3 = ['name' => 'Bilbo Bolsón', 'id' => '1'];
        self::$array4 = ['id' => 2, 'name' => 'Frodo Bolsón'];

        self::$object1 = (object) self::$array1;
        self::$object2 = (object) self::$array2;
        self::$object3 = (object) self::$array3;
        self::$object4 = (object) self::$array4;
    }

    /**
     * @dataProvider dataForIdentical
     */
    public function testIdentical(
        Record $a,
        array|object $b,
        bool $expected
    ): void {
        self::assertSame($expected, $a->is($b));
        self::assertSame(!$expected, $a->isNot($b));

        if ($b instanceof Record) {
            self::assertSame($expected, $b->is($a));
            self::assertSame(!$expected, $b->isNot($a));
        }
    }

    public function dataForIdentical(): Generator
    {
        self::setUpBeforeClass();

        yield [self::$record1, self::$record1, true];
        yield [self::$record1, clone self::$record1, true];
        yield [self::$record1, self::$record2, false];
        yield [self::$record1, self::$record3, false];
        yield [self::$record1, self::$record4, false];
        yield [self::$record1, self::$object1, false];
        yield [self::$record1, self::$object2, false];
        yield [self::$record1, self::$object3, false];
        yield [self::$record1, self::$object4, false];
        yield [self::$record1, self::$array1, false];
        yield [self::$record1, self::$array2, false];
        yield [self::$record1, self::$array3, false];
        yield [self::$record1, self::$array4, false];
    }

    /**
     * @dataProvider dataForEqual
     */
    public function testEqual(
        Record $a,
        array|object $b,
        bool $expected
    ): void {
        self::assertSame($expected, $a->isEqual($b));
        self::assertSame(!$expected, $a->isNotEqual($b));

        if ($b instanceof Record) {
            self::assertSame($expected, $b->isEqual($a));
            self::assertSame(!$expected, $b->isNotEqual($a));
        }
    }

    public function dataForEqual(): Generator
    {
        self::setUpBeforeClass();

        yield [self::$record1, self::$record1, true];
        yield [self::$record1, clone self::$record1, true];
        yield [self::$record1, self::$record2, true];
        yield [self::$record1, self::$record3, false];
        yield [self::$record1, self::$record4, false];
        yield [self::$record1, self::$object1, true];
        yield [self::$record1, self::$object2, true];
        yield [self::$record1, self::$object3, false];
        yield [self::$record1, self::$object4, false];
        yield [self::$record1, self::$array1, true];
        yield [self::$record1, self::$array2, true];
        yield [self::$record1, self::$array3, false];
        yield [self::$record1, self::$array4, false];
    }

    /**
     * @dataProvider dataForSimilar
     */
    public function testSimilar(
        Record $a,
        array|object $b,
        bool $expected
    ): void {
        self::assertSame($expected, $a->isSimilar($b));
        self::assertSame(!$expected, $a->isNotSimilar($b));

        if ($b instanceof Record) {
            self::assertSame($expected, $b->isSimilar($a));
            self::assertSame(!$expected, $b->isNotSimilar($a));
        }
    }

    public function dataForSimilar(): Generator
    {
        self::setUpBeforeClass();

        yield [self::$record1, self::$record1, true];
        yield [self::$record1, clone self::$record1, true];
        yield [self::$record1, self::$record2, true];
        yield [self::$record1, self::$record3, true];
        yield [self::$record1, self::$record4, false];
        yield [self::$record1, self::$object1, true];
        yield [self::$record1, self::$object2, true];
        yield [self::$record1, self::$object3, true];
        yield [self::$record1, self::$object4, false];
        yield [self::$record1, self::$array1, true];
        yield [self::$record1, self::$array2, true];
        yield [self::$record1, self::$array3, true];
        yield [self::$record1, self::$array4, false];
    }
}
