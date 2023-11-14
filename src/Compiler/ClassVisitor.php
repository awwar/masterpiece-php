<?php

namespace Awwar\MasterpiecePhp\Compiler;

class ClassVisitor implements ClassVisitorInterface
{
    private array $classes = [];

    public function createClass(string $namespace, string $name, string $code): void
    {
        $this->classes[$namespace][$name] = $code;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
