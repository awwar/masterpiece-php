<?php

namespace Awwar\MasterpiecePhp\Compiler;

class ClassVisitor implements ClassVisitorInterface
{
    private array $classes = [];

    public function createClass(string $className, string $body): void
    {
        $this->classes[$className] = $body;
    }

    /**
     * @return array<string, string>
     */
    public function getClasses(): array
    {
        return $this->classes;
    }
}
