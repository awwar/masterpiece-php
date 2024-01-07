<?php

namespace Awwar\MasterpiecePhp\Compiler\Util;

class NodeName
{
    public function __construct(private string $addonName, private string $nodeName)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_node', $this->addonName, $this->nodeName);
    }
}
