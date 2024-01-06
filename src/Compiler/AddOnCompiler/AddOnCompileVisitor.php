<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface
{
    private array $nodes = [];

    private array $contracts = [];

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

    public function setContract(Contract $contract): void
    {
        $name = $contract->getName();

        if (isset($this->contracts[$name])) {
            throw new RuntimeException(sprintf('Contract %s already declared', $name));
        }

        $this->contracts[$name] = $contract;
    }

    /**
     * @return Contract[]
     */
    public function getContracts(): array
    {
        return array_values($this->contracts);
    }
}
