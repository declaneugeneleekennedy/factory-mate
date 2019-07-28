<?php

namespace DevDeclan\FactoryMate;

use Psr\Log\LoggerInterface;

class FactoryMate
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
     * @param StorageInterface $storage
     * @param EntityBuilderInterface $entityBuilder
     * @param DefinitionFactoryInterface $definitionFactory
     * @param GeneratorFactoryInterface $generatorFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        StorageInterface $storage,
        EntityBuilderInterface $entityBuilder,
        DefinitionFactoryInterface $definitionFactory,
        GeneratorFactoryInterface $generatorFactory,
        LoggerInterface $logger
    ) {
        $this->storage = $storage;
        $this->entityBuilder = $entityBuilder;
        $this->definitionFactory = $definitionFactory;
        $this->generatorFactory = $generatorFactory;
        $this->logger = $logger;
    }

    public function save($entity)
    {
        return $this->storage->save($entity);
    }

    public function delete($entity)
    {
        return $this->storage->delete($entity);
    }

    public function load(string $className, $id)
    {
        return $this->storage->load($className, $id);
    }

    public function loadBy(string $className, string $property, $value)
    {
        return $this->storage->loadBy($className, $property, $value);
    }

    public function loadRandom(string $className)
    {
        return $this->storage->loadRandom($className);
    }

    public function forEach(string $className)
    {
        return $this->storage->forEach($className);
    }

    public function forEachBy(string $className, string $property, $value)
    {
        return $this->storage->forEachBy($className, $property, $value);
    }

    public function create(string $className, array $attributes = [], $fn = null)
    {
        $entity = $this->save($this->make($className, $attributes));

        if ($entity && $fn && is_callable($fn)) {
            return $fn($this, $entity);
        }

        return $entity;
    }

    public function seed(string $className, int $times, array $attributes = [], $fn = null): array
    {
        $entities = [];
        for ($i = 0; $i < $times; ++$i) {
            $entities[] = $this->create($className, $attributes, $fn);
        }

        return $entities;
    }

    protected function make(string $className, array $attributes = [])
    {
        $definition = $this->definitionFactory->getFor($className);

        $combinedAttributes = array_merge($definition->getDefaultAttributes(), $attributes);

        $processedAttributes = [];
        foreach ($combinedAttributes as $key => $value) {
            $generator = $this->generatorFactory->getFor($value);

            $processedAttributes[$key] = $generator->generate($this, $value);
        }

        $entity = $this->entityBuilder->build(
            $className,
            $processedAttributes
        );

        $prepareCallback = $definition->getPrepareCallback();

        if ($prepareCallback) {
            return $prepareCallback($entity);
        }

        return $entity;
    }
}
