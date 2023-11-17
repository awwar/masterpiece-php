<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

class AddOnNode
{
    //ToDo: output set
    public function __construct(private string $name, private NodeInputSet $input, private string $body)
    {
    }

    public function getInput(): NodeInputSet
    {
        return $this->input;
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
