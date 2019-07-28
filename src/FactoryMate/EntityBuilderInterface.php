<?php

namespace DevDeclan\FactoryMate;

interface EntityBuilderInterface
{
    public function build(string $className, array $attributes);
}
