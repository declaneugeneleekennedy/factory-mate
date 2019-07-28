<?php

namespace DevDeclan\FactoryMate;

interface DefinitionFactoryInterface
{
    public function getFor(string $className): DefinitionInterface;
}
