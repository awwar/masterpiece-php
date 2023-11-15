<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

class NodeInput
{
    public function __construct(private string $name, private mixed $type)
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
}
