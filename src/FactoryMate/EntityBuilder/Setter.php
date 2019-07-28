<?php

namespace DevDeclan\FactoryMate\EntityBuilder;

use DevDeclan\FactoryMate\EntityBuilderInterface;

class Setter implements EntityBuilderInterface
{
    /**
     * @param string $className
     * @param array $attributes
     * @return mixed
     */
    public function build(string $className, array $attributes)
    {
        $entity = new $className();

        foreach ($attributes as $key => $value) {
            $method = 'set' . ucfirst($key);
            $entity->$method($value);
        }

        return $entity;
    }
}
