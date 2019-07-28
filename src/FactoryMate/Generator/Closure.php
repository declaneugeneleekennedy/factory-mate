<?php

namespace DevDeclan\FactoryMate\Generator;

use DevDeclan\FactoryMate\GeneratorFactory\StandardCompatibleInterface;
use DevDeclan\FactoryMate\FactoryMate;

class Closure implements StandardCompatibleInterface
{
    /**
     * @param mixed $attribute
     * @return bool
     */
    public function canGenerateFor($attribute): bool
    {
        /**
         * We do NOT support string callable references despite PHP saying they're OK. The reason for this is that they
         * are indistinguishable from a plain string, and to make matters worse PHP function names don't care about the
         * case you use when calling them.
         *
         * This can introduce extremely hard to find bugs. For example, setting a string attribute to "system" or even
         * "System" will cause PHP to declare that it is a callable, because there is a function called system(). For
         * the time being, if you want to use a named function, just wrap it in a closure.
         */
        if (is_string($attribute)) {
            return false;
        }

        return is_callable($attribute);
    }

    /**
     * @param FactoryMate $factoryMate
     * @param mixed $attribute
     * @return mixed
     */
    public function generate(FactoryMate $factoryMate, $attribute)
    {
        return $attribute($factoryMate);
    }
}
