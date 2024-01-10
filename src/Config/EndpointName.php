<?php

namespace Awwar\MasterpiecePhp\Config;

class EndpointName
{
    public function __construct(private string $addonName, private string $contractName)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_endpoint', $this->addonName, $this->contractName);
    }
}
