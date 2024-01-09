<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface MethodGeneratorInterface
{
    public function addComment(string $comment): MethodGeneratorInterface;

    public function addParameter(string $name, string $type): MethodGeneratorInterface;

    public function setReturnType(?string $type): MethodGeneratorInterface;

    public function getBodyGenerator(): MethodBodyGeneratorInterface;

    public function makeStatic(): MethodGeneratorInterface;

    public function end(): ClassGeneratorInterface;
}
