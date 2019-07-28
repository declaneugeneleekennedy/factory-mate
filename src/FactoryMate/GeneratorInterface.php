<?php

namespace DevDeclan\FactoryMate;

interface GeneratorInterface
{
    public function generate(FactoryMate $factoryMate, $attribute);
}
