<?php

namespace DevDeclan\Test\FactoryMate\EntityAccessor;

use DevDeclan\FactoryMate\EntityAccessor\Simple;
use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testSimple()
    {
        $entity = new class()
        {
            public $id = 1;
        };

        $simple = new Simple();

        $this->assertEquals(1, $simple->access($entity, 'id'));
    }
}
