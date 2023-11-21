<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use RuntimeException;

class AppAddonVisitor implements AddOnCompileVisitorInterface
{
    private array $nodes = [];

    public function setNode(AddOnNode $node): void
    {
        $name = $node->getName();

        if (isset($this->nodes[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodes[$name] = $node;
    }

    /**
     * @return AddOnNode[]
     */
    public function getNodes(): array
    {
        return array_values($this->nodes);
    }
}
