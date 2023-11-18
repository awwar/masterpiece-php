<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use Closure;

class AddOnNode
{
    //ToDo: output set
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

    public function getBody(array $options): string
    {
        return call_user_func($this->body, $options);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isOptionsRequired(): bool
    {
        return false === empty($options);
    }
}
