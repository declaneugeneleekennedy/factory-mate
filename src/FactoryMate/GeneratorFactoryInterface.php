<?php

namespace DevDeclan\FactoryMate;

interface GeneratorFactoryInterface
{
    public function getFor($attribute): GeneratorInterface;
}
