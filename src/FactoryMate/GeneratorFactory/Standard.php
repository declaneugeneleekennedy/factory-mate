<?php

namespace DevDeclan\FactoryMate\GeneratorFactory;

use DevDeclan\FactoryMate\GeneratorFactoryInterface;
use DevDeclan\FactoryMate\GeneratorInterface;

class Standard implements GeneratorFactoryInterface
{
    /**
     * @var GeneratorInterface
     */
    protected $fallback;

    /**
     * @var GeneratorInterface[]
     */
    protected $generators;

    /**
     * @param GeneratorInterface $fallback
     * @param array $generators
     */
    public function __construct(GeneratorInterface $fallback, array $generators = [])
    {
        $this->fallback = $fallback;
        $this->generators = $generators;
    }

    /**
     * @param GeneratorInterface $generator
     * @return Standard
     */
    public function add(GeneratorInterface $generator): Standard
    {
        $this->generators[] = $generator;

        return $this;
    }

    /**
     * @param string $attribute
     * @return GeneratorInterface
     */
    public function getFor($attribute): GeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if ($generator->canGenerateFor($attribute)) {
                return $generator;
            }
        }

        return $this->getFallback();
    }

    /**
     * @return GeneratorInterface
     */
    public function getFallback(): GeneratorInterface
    {
        return $this->fallback;
    }
}
