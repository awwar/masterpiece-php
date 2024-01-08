<?php

namespace Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext;

use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

class FragmentCompileContext
{
    private bool $isSkip = false;

    public function __construct(
        private MethodBodyGeneratorInterface $methodBodyGenerator,
        private string $socketName,
        private array $args,
        private string $nodeName,
        private string $functionName
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
}
