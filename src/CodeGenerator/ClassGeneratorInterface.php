<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface ClassGeneratorInterface
{
    public function addComment(string $comment): ClassGeneratorInterface;

    public function setNamespace(string $name): ClassGeneratorInterface;

    public function addUsing(string $name, ?string $alias = null): ClassGeneratorInterface;

    public function addMethod(string $name): MethodGeneratorInterface;

    public function addProperty(string $name, string $type, string $default): ClassGeneratorInterface;

    public function generate(): string;
}
