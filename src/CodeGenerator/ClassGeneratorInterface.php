<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface ClassGeneratorInterface
{
    public function setNamespace(string $name): ClassGeneratorInterface;

    public function addUsing(string $name, ?string $alias = null): ClassGeneratorInterface;

    public function addMethod(string $name): MethodGeneratorInterface;

    public function generate(): string;
}
