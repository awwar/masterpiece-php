<?php

namespace Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext;

use Awwar\MasterpiecePhp\AddOn\NodeTemplateObtainerInterface;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGeneratorInterface;

class EndpointBodyCompileContext
{
    public function __construct(
        private ClassGeneratorInterface $classGenerator,
        private NodeTemplateObtainerInterface $nodeTemplateObtainer,
        private string $endpointName,
        private array $params
    ) {
    }

    public function getClassGenerator(): ClassGeneratorInterface
    {
        return $this->classGenerator;
    }

    public function getNodeTemplateObtainer(): NodeTemplateObtainerInterface
    {
        return $this->nodeTemplateObtainer;
    }

    public function getEndpointName(): string
    {
        return $this->endpointName;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
