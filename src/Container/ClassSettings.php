<?php

namespace Awwar\MasterpiecePhp\Container;

class ClassSettings
{
    public function __construct(private string $fqcn, private array $constructorParams=[])
    {

    }

    public function getFqcn(): string
    {
        return $this->fqcn;

    }

    public function getConstructorParams(): array
    {
        return $this->constructorParams;

    }
}
