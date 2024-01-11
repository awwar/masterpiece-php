<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\ContractTemplate;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointTemplate;
use Awwar\MasterpiecePhp\AddOn\Node\NodeTemplate;
use Awwar\MasterpiecePhp\AddOn\NodeTemplateObtainerInterface;
use RuntimeException;

class AddOnCompileVisitor implements AddOnCompileVisitorInterface, NodeTemplateObtainerInterface
{
    private array $nodeTemplates = [];

    private array $contractTemplates = [];

    private array $endpointTemplates = [];

    public function setNodeTemplate(NodeTemplate $nodeTemplate): void
    {
        $name = $nodeTemplate->getFullName();

        if (isset($this->nodeTemplates[$name])) {
            throw new RuntimeException(sprintf('Node %s already declared', $name));
        }

        $this->nodeTemplates[$name] = $nodeTemplate;
    }

    public function getNodeTemplate(string $nodeFullName): NodeTemplate
    {
        return $this->nodeTemplates[$nodeFullName];
    }

    /**
     * @return NodeTemplate[]
     */
    public function getNodeTemplates(): array
    {
        return array_values($this->nodeTemplates);
    }

    public function setContractTemplate(ContractTemplate $contractTemplate): void
    {
        $name = $contractTemplate->getFullName();

        if (isset($this->contractTemplates[$name])) {
            throw new RuntimeException(sprintf('ContractTemplate %s already declared', $name));
        }

        $this->contractTemplates[$name] = $contractTemplate;
    }

    /**
     * @return ContractTemplate[]
     */
    public function getContractTemplates(): array
    {
        return array_values($this->contractTemplates);
    }

    public function setEndpointTemplate(EndpointTemplate $endpointTemplate): void
    {
        $name = $endpointTemplate->getFullName();

        if (isset($this->endpointTemplates[$name])) {
            throw new RuntimeException(sprintf('Endpoint %s already declared', $name));
        }

        $this->endpointTemplates[$name] = $endpointTemplate;
    }

    /**
     * @return EndpointTemplate[]
     */
    public function getEndpointTemplates(): array
    {
        return array_values($this->endpointTemplates);
    }
}
