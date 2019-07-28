<?php

namespace DevDeclan\FactoryMate\DefinitionFactory\Standard;

use DevDeclan\FactoryMate\DefinitionFactory\DefinitionNotFoundExceptionInterface;
use RuntimeException;

class DefinitionNotFoundException extends RuntimeException implements DefinitionNotFoundExceptionInterface
{
}
