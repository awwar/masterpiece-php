<?php

namespace Awwar\MasterpiecePhp\AddOn\Structure;

class AddOnStructure
{
    public function __construct(private string $name, private string $body)
    {
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
