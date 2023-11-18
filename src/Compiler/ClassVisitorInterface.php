<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\CodeGenerator\ClassGeneratorInterface;

interface ClassVisitorInterface
{
    public function createClass(string $className, ClassGeneratorInterface $classGenerator): void;
}
