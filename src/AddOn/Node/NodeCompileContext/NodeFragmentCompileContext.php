<?php

namespace Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext;

use Awwar\MasterpiecePhp\AddOn\SubcompileInterface;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

class NodeFragmentCompileContext
{
    private bool $isSkip = false;

    public function __construct(
        private MethodBodyGeneratorInterface $methodBodyGenerator,
        private SubcompileInterface $subcompile,
        private string $socketName,
        private array $args,
        private string $nodeName,
        private string $functionName,
        private array $options = []
    ) {
    }

    public function getMethodBodyGenerator(): MethodBodyGeneratorInterface
    {
        return $this->methodBodyGenerator;
    }

    public function skip(): void
    {
        $this->isSkip = true;
    }

    public function isSkip(): bool
    {
        return $this->isSkip;
    }

    public function getSocketName(): string
    {
        return $this->socketName;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getNodeName(): string
    {
        return $this->nodeName;
    }

    public function getMethodName(): string
    {
        return $this->functionName;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSubcompile(): SubcompileInterface
    {
        return $this->subcompile;
    }
}
