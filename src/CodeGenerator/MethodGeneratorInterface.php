<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface MethodGeneratorInterface
{
    public function addArgument(string $name, string $type): MethodGeneratorInterface;

    public function setReturnType(string $type): MethodGeneratorInterface;

    public function setBody(string $body): MethodGeneratorInterface;

    public function getClass(): ClassGeneratorInterface;
}
