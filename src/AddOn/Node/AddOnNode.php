<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Closure;

class AddOnNode
{
    public function __construct(
        private string $name,
        private NodeInputSet $input,
        private NodeOutput $output,
        private Closure $body,
        private array $options = []
    ) {
    }

    public function getInput(): NodeInputSet
    {
        return $this->input;
    }

    public function getOutput(): NodeOutput
    {
        return $this->output;
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
