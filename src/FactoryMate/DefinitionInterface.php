<?php

namespace DevDeclan\FactoryMate;

interface DefinitionInterface
{
    public function getEntityClassName(): string;
    public function getDefaultAttributes(): array;
    public function getPrepareCallback();
}
