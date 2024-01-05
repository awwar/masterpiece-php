<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface MethodGeneratorInterface
{
    public function addComment(string $comment): MethodGeneratorInterface;

    public function addArgument(string $name, string $type): MethodGeneratorInterface;

    public function setReturnType(string $type): MethodGeneratorInterface;

    public function getBodyGenerator(): MethodBodyGeneratorInterface;

    public function getClass(): ClassGeneratorInterface;
}
