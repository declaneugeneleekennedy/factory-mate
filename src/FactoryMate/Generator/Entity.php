<?php

namespace DevDeclan\FactoryMate\Generator;

use DevDeclan\FactoryMate\EntityAccessorInterface;
use DevDeclan\FactoryMate\GeneratorFactory\StandardCompatibleInterface;
use DevDeclan\FactoryMate\FactoryMate;

class Entity implements StandardCompatibleInterface
{
    /**
     * @var EntityAccessorInterface
     */
    protected $entityAccessor;

    /**
     * @param EntityAccessorInterface $entityAccessor
     */
    public function __construct(EntityAccessorInterface $entityAccessor)
    {
        $this->entityAccessor = $entityAccessor;
    }

    /**
     * @param mixed $attribute
     * @return bool
     */
    public function canGenerateFor($attribute): bool
    {
        if (!is_string($attribute)) {
            return false;
        }

        if (preg_match('/^entity\|.+$/', $attribute)) {
            return true;
        }

        return false;
    }

    /**
     * @param FactoryMate $factoryMate
     * @param mixed $attribute
     * @return mixed
     */
    public function generate(FactoryMate $factoryMate, $attribute)
    {
        list(, $className) = explode('|', $attribute);

        $entity = $factoryMate->create($className);

        return $this->entityAccessor->access($entity, 'id');
    }
}
