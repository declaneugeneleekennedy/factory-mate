<?php

namespace DevDeclan\Test\FactoryMate\Generator;

use DevDeclan\FactoryMate\EntityAccessor;
use DevDeclan\FactoryMate\FactoryMate;
use DevDeclan\FactoryMate\Generator\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function canGenerateForProvider()
    {
        return [
            ['entity|Foo\\Bar', true],
            ['entity but not the pipe', false],
            ['this|should not even be considered', false],
            [['hi'], false],
        ];
    }

    /**
     * @param string $attribute
     * @param bool $expected
     * @dataProvider canGenerateForProvider
     */
    public function testCanGenerateFor($attribute, bool $expected)
    {
        $generator = new Entity(new EntityAccessor\Simple());

        $this->assertEquals($expected, $generator->canGenerateFor($attribute));
    }

    public function testGenerate()
    {
        $entity = (object) [
            'id' => 1,
            'name' => 'Cat'
        ];

        $fm = $this->prophesize(FactoryMate::class);

        $fm->create('Foo\\Bar')->willReturn($entity);

        $generator = new Entity(new EntityAccessor\Simple());

        $this->assertEquals(1, $generator->generate($fm->reveal(), 'entity|Foo\\Bar'));
    }
}
