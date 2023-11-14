<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\NodeExecutable;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface
{
    private array $nodes = [];

    public function createNode(string $name, NodeExecutable $executable): void
    {
        $this->nodes[$name] = $executable;
    }

    /**
     * @return array<string, NodeExecutable>
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }
}
