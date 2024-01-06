<?php

namespace Awwar\MasterpiecePhp\AddOn\Contract;

class Contract
{
    private array $castFrom = [];

    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addCastFrom(string $contractName, string $nodeName, string $methodName): void
    {
        $this->castFrom[$contractName] = [$nodeName, $methodName];
    }

    /**
     * @return array
     */
    public function getCastFrom(): array
    {
        return $this->castFrom;
    }
}
