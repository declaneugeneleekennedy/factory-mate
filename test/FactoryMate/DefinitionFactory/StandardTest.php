<?php

namespace DevDeclan\Test\FactoryMate\DefinitionFactory;

use DevDeclan\FactoryMate\DefinitionFactory\ProviderInterface;
use DevDeclan\FactoryMate\DefinitionFactory\Standard;
use DevDeclan\FactoryMate\DefinitionInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class StandardTest extends TestCase
{
    /**
     * @var Standard
     */
    protected $instance;

    protected $definition;

    public function setUp()
    {
        parent::setUp();

        $this->instance = new Standard();

        $this->definition = new class() implements DefinitionInterface {
            public function getEntityClassName(): string
            {
                return get_class($this);
            }

            public function getDefaultAttributes(): array
            {
                return [
                    'id' => 1,
                    'name' => 'Cat',
                ];
            }

            public function getPrepareCallback()
            {
                return null;
            }
        };
    }

    public function testAddWithObject()
    {
        $this->instance->add($this->definition);

        $this->assertEquals(
            $this->definition,
            $this->instance->getFor(get_class($this->definition))
        );
    }

    public function testAddWithClassName()
    {
        $this->instance->addClass(get_class($this->definition));

        $this->assertEquals(
            $this->definition,
            $this->instance->getFor(get_class($this->definition))
        );
    }

    public function testRegister()
    {
        $definition = new class() implements DefinitionInterface {
            public function getEntityClassName(): string
            {
                return 'Foo';
            }

            public function getDefaultAttributes(): array
            {
                return [];
            }

            public function getPrepareCallback()
            {
                return null;
            }
        };

        $provider = $this->prophesize(ProviderInterface::class);

        $provider->getDefinitions()->willReturn([$definition]);

        $this->instance->register($provider->reveal());

        $this->assertEquals($definition, $this->instance->getFor(get_class($definition)));
    }

    public function testGetForWithBadDefinition()
    {
        $this->expectException(Standard\DefinitionNotFoundException::class);
        $this->expectExceptionMessage('No definition found for DoesNotExist');

        $this->instance->getFor('DoesNotExist');
    }

    public function testRegisterWithBadDefinition()
    {
        $provider = $this->prophesize(ProviderInterface::class);

        $provider->getDefinitions()->willReturn([new stdClass()]);

        $this->expectException(Standard\InvalidDefinitionException::class);
        $this->expectExceptionMessage(
            'Provider ' . get_class($provider->reveal()) . ' returned a definition which does not implement ' .
            'DevDeclan\FactoryMate\DefinitionInterface: stdClass'
        );

        $this->instance->register($provider->reveal());
    }
}
