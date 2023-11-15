<?php

namespace Awwar\MasterpiecePhp\Container;

use ReflectionParameter;

class ClassSettings
{
    public function __construct(private string $fqcn, private array $constructorParams = [])
    {
    }

    public function getFqcn(): string
    {
        return $this->fqcn;
    }

    /**
     * @return ReflectionParameter[]
     */
    public function getConstructorParams(): array
    {
        return $this->constructorParams;
    }
}
