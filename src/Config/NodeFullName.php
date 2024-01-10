<?php

namespace Awwar\MasterpiecePhp\Config;

class NodeFullName
{
    public function __construct(private string $addonName, private string $nodePatternName)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_node', $this->addonName, $this->nodePatternName);
    }

    public function getAddonName(): string
    {
        return $this->addonName;
    }

    public function getNodePatternName(): string
    {
        return $this->nodePatternName;
    }
}
