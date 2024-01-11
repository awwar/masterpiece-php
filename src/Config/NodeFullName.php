<?php

namespace Awwar\MasterpiecePhp\Config;

class NodeFullName
{
    public function __construct(private string $addonName, private string $nodeTemplateName)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_node', $this->addonName, $this->nodeTemplateName);
    }

    public function getAddonName(): string
    {
        return $this->addonName;
    }

    public function getNodeTemplateName(): string
    {
        return $this->nodeTemplateName;
    }
}
