<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\CodeGenerator\ClassGeneratorInterface;

class ClassVisitor implements ClassVisitorInterface
{
    private array $classes = [];

    public function createClass(string $className, ClassGeneratorInterface $classGenerator): void
    {
        $this->classes[$className] = $classGenerator;
    }

    /**
     * @return ClassGeneratorInterface[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }
}
