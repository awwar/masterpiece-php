<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

class NodeOutput
{
    public function __construct(private string $name, private mixed $type, private bool $hasOutput = true)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): mixed
    {
        return $this->type;
    }

    public function isHasOutput(): bool
    {
        return $this->hasOutput;
    }

    public static function noOutput(): self
    {
        return new self('', null, false);
    }
}
