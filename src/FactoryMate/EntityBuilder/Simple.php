<?php

namespace DevDeclan\FactoryMate\EntityBuilder;

use DevDeclan\FactoryMate\EntityBuilderInterface;

class Simple implements EntityBuilderInterface
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
            $entity->$key = $value;
        }

        return $entity;
    }
}
