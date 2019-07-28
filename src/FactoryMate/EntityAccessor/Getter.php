<?php

namespace DevDeclan\FactoryMate\EntityAccessor;

use DevDeclan\FactoryMate\EntityAccessor\Getter\GetterNotFoundException;
use DevDeclan\FactoryMate\EntityAccessorInterface;

class Getter implements EntityAccessorInterface
{
    /**
     * @param object $entity
     * @param string $property
     * @return mixed
     * @throws GetterNotFoundException
     */
    public function access($entity, $property)
    {
        $method = 'get' . ucfirst($property);

        if (!method_exists($entity, $method)) {
            throw new GetterNotFoundException(sprintf(
                'No getter found for property %s (tried %s)',
                $property,
                $method
            ));
        }

        return $entity->$method();
    }
}
