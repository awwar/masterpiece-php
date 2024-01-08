<?php

namespace Awwar\MasterpiecePhp\AddOn\Fragment;

use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Closure;

class Fragment
{
    public function __construct(
        private string $name,
        private Closure $body,
        private array $options = []
    ) {
    }

    public function compileBody(MethodBodyGeneratorInterface $methodBodyGenerator, array $options): void
    {
        call_user_func($this->body, $methodBodyGenerator, $options);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isOptionsRequired(): bool
    {
        return false === empty($this->options);
    }
}
