<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ConfigVisitorInterface
{
    public function persistNodePatternOption(string $nodeName, string $pattern, array $settings): void;

    public function isNodeDemand(string $pattern): bool;
}
