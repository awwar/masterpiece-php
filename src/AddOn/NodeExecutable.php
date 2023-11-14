<?php

namespace Awwar\MasterpiecePhp\AddOn;

class NodeExecutable
{
    public function __construct(private array $input, private string $body)
    {
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
