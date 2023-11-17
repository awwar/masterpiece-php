<?php

namespace Awwar\MasterpiecePhp\Config;

class Config implements ConfigInterface
{
    public function __construct(private string $name, private string $type, private array $params)
    {
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
