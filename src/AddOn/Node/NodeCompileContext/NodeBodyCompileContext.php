<?php

namespace Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext;

use Awwar\MasterpiecePhp\AddOn\NodeTemplateObtainerInterface;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

class NodeBodyCompileContext
{
    private bool $isSkip = false;

    public function __construct(private MethodBodyGeneratorInterface $methodBodyGenerator, private array $options, private NodeTemplateObtainerInterface $nodeTemplateObtain)
    {
    }

    public function getMethodBodyGenerator(): MethodBodyGeneratorInterface
    {
        return $this->methodBodyGenerator;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function skip(): void
    {
        $this->isSkip = true;
    }

    public function isSkip(): bool
    {
        return $this->isSkip;
    }

    public function getNodeTemplateObtain(): NodeTemplateObtainerInterface
    {
        return $this->nodeTemplateObtain;
    }
}
