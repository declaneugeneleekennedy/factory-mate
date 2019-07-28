<?php

namespace DevDeclan\FactoryMate\EntityAccessor;

use DevDeclan\FactoryMate\EntityAccessorInterface;

class Simple implements EntityAccessorInterface
{
    /**
     * @param object $entity
     * @param mixed $property
     * @return mixed
     */
    public function access($entity, $property)
    {
        return $entity->$property ?: null;
    }
}
