<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use Awwar\MasterpiecePhp\AddOn\NodePatternObtainerInterface;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface, NodePatternObtainerInterface
{
    private array $nodePatterns = [];

    private array $contracts = [];

    public function setNodePattern(NodePattern $nodePattern): void
    {
        $name = $nodePattern->getFullName();

        if (isset($this->nodePatterns[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodePatterns[$name] = $nodePattern;
    }

    public function getNodePattern(string $addonName, string $nodeName): NodePattern
    {
        $nodeName = new NodeName($addonName, $nodeName);

        return $this->nodePatterns[(string) $nodeName];
    }

    /**
     * @return NodePattern[]
     */
    public function getNodesPatterns(): array
    {
        return array_values($this->nodePatterns);
    }

    public function setContract(Contract $contract): void
    {
        $name = $contract->getFullName();

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
