<?php

namespace DevDeclan\Test\FactoryMate;

use DevDeclan\FactoryMate\DefinitionFactoryInterface;
use DevDeclan\FactoryMate\DefinitionInterface;
use DevDeclan\FactoryMate\EntityAccessor;
use DevDeclan\FactoryMate\EntityBuilder;
use DevDeclan\FactoryMate\EntityBuilderInterface;
use DevDeclan\FactoryMate\FactoryMate;
use DevDeclan\FactoryMate\Generator;
use DevDeclan\FactoryMate\GeneratorFactory;
use DevDeclan\FactoryMate\GeneratorFactoryInterface;
use DevDeclan\FactoryMate\StorageInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FactoryMateTest extends TestCase
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var EntityBuilderInterface
     */
    protected $entityBuilder;

    /**
     * @var DefinitionFactoryInterface
     */
    protected $definitionFactory;

    /**
     * @var GeneratorFactoryInterface
     */
    protected $generatorFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var FactoryMate
     */
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->storage = $this->prophesize(StorageInterface::class);

        $this->entityBuilder = new EntityBuilder\Simple();

        $this->definitionFactory = $this->prophesize(DefinitionFactoryInterface::class);

        $this->generatorFactory = new GeneratorFactory\Standard(new Generator\PassThru());

        $this->generatorFactory
            ->add(new Generator\Closure())
            ->add(new Generator\Entity(new EntityAccessor\Simple()));

        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->instance = new FactoryMate(
            $this->storage->reveal(),
            $this->entityBuilder,
            $this->definitionFactory->reveal(),
            $this->generatorFactory,
            $this->logger->reveal()
        );
    }

    public function storageMethodCallProvider()
    {
        $entityDouble = (object)[
            'id' => 1,
            'name' => 'Cat',
        ];

        return [
            ['save', [$entityDouble], $entityDouble],
            ['delete', [$entityDouble], true],
            ['load', ['Foo\\Bar', 1], $entityDouble],
            ['loadBy', ['Foo\\Bar', 'baz', 1], $entityDouble],
            ['loadRandom', ['Foo\\Bar'], $entityDouble],
            ['forEach', ['Foo\\Bar'], [$entityDouble]],
            ['forEachBy', ['Foo\\Bar', 'baz', 1], [$entityDouble]],
        ];
    }

    /**
     * @param string $method
     * @param array $args
     * @param mixed $result
     * @dataProvider storageMethodCallProvider
     */
    public function testStorageMethodCall(string $method, array $args, $result)
    {
        $this->storage->$method(...$args)
            ->shouldBeCalledTimes(1)
            ->willReturn($result);

        $this->assertEquals($result, $this->instance->$method(...$args));
    }

    public function testSeedAndThusCreatePlusMake()
    {
        $double = new class() {
            public $id;

            public $name;

            public $prepared = false;

            public $saved = false;
        };

        $className = get_class($double);

        $entityOne = new $className();

        $entityOne->id = 1;
        $entityOne->name = 'Cat';
        $entityOne->prepared= true;
        $entityOne->saved = true;

        $entityTwo = new $className();

        $entityTwo->id = 2;
        $entityTwo->name = 'Cat';
        $entityTwo->prepared= true;
        $entityTwo->saved = true;

        $definition = $this->prophesize(DefinitionInterface::class);

        $definition->getDefaultAttributes()->willReturn(['name' => 'Cat']);

        $definition->getPrepareCallback()->willReturn(function ($entity) {
            $entity->prepared = true;

            return $entity;
        });

        $definition->getEntityClassName()->willReturn($className);

        $this->definitionFactory->getFor($className)->willReturn($definition->reveal());

        $preSaveEntityOne = clone $entityOne;
        $preSaveEntityOne->saved = false;

        $preSaveEntityTwo = clone $entityTwo;
        $preSaveEntityTwo->saved = false;

        $this->storage->save($preSaveEntityOne)->willReturn($preSaveEntityOne);
        $this->storage->save($preSaveEntityTwo)->willReturn($preSaveEntityTwo);

        $attributes = [
            'id' => function () {
                static $id = 0;

                ++$id;

                return $id;
            },
        ];

        $fn = function (FactoryMate $fm, $entity) {
            $entity->saved = true;

            return $entity;
        };

        $this->assertEquals(
            [$entityOne, $entityTwo],
            $this->instance->seed(2, $className, $attributes, $fn)
        );
    }
}
