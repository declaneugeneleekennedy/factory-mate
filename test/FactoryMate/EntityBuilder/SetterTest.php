<?php

namespace DevDeclan\Test\FactoryMate\EntityBuilder;

use DevDeclan\FactoryMate\EntityBuilder\Setter;
use PHPUnit\Framework\TestCase;

class SetterTest extends TestCase
{
    public function testSetter()
    {
        $entity = new class() {
            protected $id = 1;

            protected $name = 'Cat';

            public function setId($id)
            {
                $this->id = $id;

                return $this;
            }

            public function setName($name)
            {
                $this->name = $name;

                return $this;
            }
        };

        $className = get_class($entity);

        $setter = new Setter();

        $this->assertEquals($entity, $setter->build($className, [
            'id' => 1,
            'name' => 'Cat',
        ]));
    }

    public function testSetterNotFound()
    {
        $this->expectException(Setter\SetterNotFoundException::class);
        $this->expectExceptionMessage('No setter found for property foo (tried setFoo)');

        $setter = new Setter();

        $setter->build('\stdClass', ['foo' => 'bar']);
    }
}
