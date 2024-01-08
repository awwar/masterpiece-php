<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Fragment\Fragment;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface
{
    private array $nodes = [];

    private array $contracts = [];

    private array $fragments = [];

    public function setNode(NodePattern $node): void
    {
        $name = $node->getName();

        if (isset($this->nodes[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodes[$name] = $node;
    }

    /**
     * @return NodePattern[]
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

    public function setFragment(Fragment $fragment): void
    {
        $name = $fragment->getName();

        if (isset($this->fragments[$name])) {
            throw new RuntimeException(sprintf('Contract %s already declared', $name));
        }

        $this->fragments[$name] = $fragment;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments(): array
    {
        return array_values($this->fragments);
    }
}
