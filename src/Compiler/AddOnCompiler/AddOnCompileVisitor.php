<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointPattern;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use Awwar\MasterpiecePhp\AddOn\NodePatternObtainerInterface;
use Awwar\MasterpiecePhp\Config\NodeFullName;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface, NodePatternObtainerInterface
{
    private array $nodePatterns = [];

    private array $contracts = [];

    private array $endpointPatterns = [];

    public function setNodePattern(NodePattern $nodePattern): void
    {
        $name = $nodePattern->getFullName();

        if (isset($this->nodePatterns[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodePatterns[$name] = $nodePattern;
    }

    public function getNodePattern(string $nodeFullName): NodePattern
    {
        return $this->nodePatterns[$nodeFullName];
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

    public function setEndpointPattern(EndpointPattern $endpointPattern): void
    {
        $name = $endpointPattern->getFullName();

        if (isset($this->endpointPatterns[$name])) {
            throw new RuntimeException(sprintf('Endpoint %s already declared', $name));
        }

        $this->endpointPatterns[$name] = $endpointPattern;
    }

    /**
     * @return EndpointPattern[]
     */
    public function getEndpointPatterns(): array
    {
        return array_values($this->endpointPatterns);
    }
}
