<?php

namespace Awwar\MasterpiecePhp\Config;

interface ConfigInterface
{
    public function getType(): string;

    public function getName(): string;

    public function getParams(): array;
}
