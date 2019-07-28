<?php

namespace DevDeclan\FactoryMate\GeneratorFactory;

use DevDeclan\FactoryMate\GeneratorInterface;

interface StandardCompatibleInterface extends GeneratorInterface
{
    public function canGenerateFor($attribute): bool;
}
