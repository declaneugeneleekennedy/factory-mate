<?php

namespace DevDeclan\FactoryMate\DefinitionFactory;

use DevDeclan\FactoryMate\DefinitionFactory\Standard\DefinitionNotFoundException;
use DevDeclan\FactoryMate\DefinitionFactory\Standard\InvalidDefinitionException;
use DevDeclan\FactoryMate\DefinitionFactoryInterface;
use DevDeclan\FactoryMate\DefinitionInterface;

class Standard implements DefinitionFactoryInterface
{
    /**
     * @var DefinitionInterface<string>[]
     */
    protected $definitions = [];

    /**
     * @param DefinitionInterface $definition
     * @return Standard
     */
    public function add(DefinitionInterface $definition): Standard
    {
        $this->definitions[get_class($definition)] = $definition;

        return $this;
    }

    /**
     * @param string $className
     * @return Standard
     */
    public function addClass(string $className): Standard
    {
        return $this->add(new $className());
    }

    /**
     * @param ProviderInterface $provider
     * @return Standard
     */
    public function register(ProviderInterface $provider): Standard
    {
        $definitions = $provider->getDefinitions();

        foreach ($definitions as $definition) {
            if (is_a($definition, DefinitionInterface::class)) {
                $this->add($definition);
                continue;
            }

            throw new InvalidDefinitionException(sprintf(
                'Provider %s returned a definition which does not implement %s: %s',
                get_class($provider),
                DefinitionInterface::class,
                get_class($definition)
            ));
        }

        return $this;
    }

    /**
     * @param string $definitionName
     * @return DefinitionInterface
     */
    public function getFor(string $definitionName): DefinitionInterface
    {
        if (isset($this->definitions[$definitionName])) {
            return $this->definitions[$definitionName];
        }

        throw new DefinitionNotFoundException(sprintf(
            'No definition found for %s',
            $definitionName
        ));
    }
}
