<?php

namespace Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext;

use Awwar\MasterpiecePhp\AddOn\NodePatternObtainerInterface;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

class BodyCompileContext
{
    private bool $isSkip = false;

    public function __construct(private MethodBodyGeneratorInterface $methodBodyGenerator, private array $options, private NodePatternObtainerInterface $nodePatternObtain)
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

    public function getNodePatternObtain(): NodePatternObtainerInterface
    {
        return $this->nodePatternObtain;
    }
}
