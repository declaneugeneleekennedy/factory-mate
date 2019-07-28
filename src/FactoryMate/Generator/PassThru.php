<?php

namespace DevDeclan\FactoryMate\Generator;

use DevDeclan\FactoryMate\GeneratorInterface;
use DevDeclan\FactoryMate\FactoryMate;

class PassThru implements GeneratorInterface
{
    /**
     * @param FactoryMate $factoryMate
     * @param string $attribute
     * @return string
     */
    public function generate(FactoryMate $factoryMate, $attribute)
    {
        return $attribute;
    }
}
