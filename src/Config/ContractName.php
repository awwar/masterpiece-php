<?php

namespace Awwar\MasterpiecePhp\Config;

class ContractName
{
    public function __construct(private string $addonName, private string $contractName)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_contract', $this->addonName, $this->contractName);
    }
}
