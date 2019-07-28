<?php

namespace DevDeclan\FactoryMate;

use DevDeclan\FactoryMate\DefinitionFactory\DefinitionNotFoundExceptionInterface;

interface DefinitionFactoryInterface
{
    /**
     * @param string $definitionName
     * @return DefinitionInterface
     * @throws DefinitionNotFoundExceptionInterface
     */
    public function getFor(string $definitionName): DefinitionInterface;
}
