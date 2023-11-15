<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Structure\AddOnStructure;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface
{
    private array $nodes = [];
    private array $structures = [];

    public function setNode(AddOnNode $node): void
    {
        $name = $node->getName();

        if (isset($this->nodes[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodes[$name] = $node;
    }

    public function setStructure(AddOnStructure $structure): void
    {
        $name = $structure->getName();

        if (isset($this->structures[$name])) {
            throw new RuntimeException(sprintf('Structure %s already declared', $name));
        }

        $this->structures[$name] = $structure;
    }

    /**
     * @return AddOnNode[]
     */
    public function getNodes(): array
    {
        return array_values($this->nodes);
    }

    /**
     * @return AddOnStructure[]
     */
    public function getStructures(): array
    {
        return array_values($this->nodes);
    }
}
