<?php

namespace DevDeclan\FactoryMate\EntityBuilder;

use DevDeclan\FactoryMate\EntityBuilder\Setter\SetterNotFoundException;
use DevDeclan\FactoryMate\EntityBuilderInterface;

class Setter implements EntityBuilderInterface
{
    /**
     * @param string $className
     * @param array $attributes
     * @return mixed
     * @throws SetterNotFoundException
     */
    public function build(string $className, array $attributes)
    {
        $entity = new $className();

        foreach ($attributes as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (!method_exists($entity, $method)) {
                throw new SetterNotFoundException(sprintf(
                    'No setter found for property %s (tried %s)',
                    $key,
                    $method
                ));
            }

            $entity->$method($value);
        }

        return $entity;
    }
}
