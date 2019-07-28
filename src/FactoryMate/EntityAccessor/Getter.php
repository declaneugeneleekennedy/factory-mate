<?php

namespace DevDeclan\FactoryMate\EntityAccessor;

use DevDeclan\FactoryMate\EntityAccessorInterface;

class Getter implements EntityAccessorInterface
{
    /**
     * @param object $entity
     * @param string $property
     * @return mixed
     */
    public function access($entity, $property)
    {
        $method = 'get' . ucfirst($property);

        return method_exists($entity, $method) ? $entity->$method() : null;
    }
}
