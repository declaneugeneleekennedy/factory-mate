<?php

namespace DevDeclan\Test\FactoryMate\EntityAccessor;

use DevDeclan\FactoryMate\EntityAccessor\Getter;
use PHPUnit\Framework\TestCase;
use stdClass;

class GetterTest extends TestCase
{
    public function testAccess()
    {
        $entity = new class() {
            public function getId()
            {
                return 1;
            }
        };

        $getter = new Getter();

        $this->assertEquals(1, $getter->access($entity, 'id'));
    }

    public function testGetterNotFound()
    {
        $entity = new stdClass();
        $getter = new Getter();

        $this->expectException(Getter\GetterNotFoundException::class);
        $this->expectExceptionMessage('No getter found for property foo (tried getFoo)');

        $getter->access($entity, 'foo');
    }
}
