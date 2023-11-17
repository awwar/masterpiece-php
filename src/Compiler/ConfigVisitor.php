<?php

namespace Awwar\MasterpiecePhp\Compiler;

class ConfigVisitor implements ConfigVisitorInterface
{
    private array $nodesSettings = [];
    private array $nodesPatterns = [];
    private array $patternsNodes = [];

    public function persistNodePatternOption(string $nodeName, string $pattern, array $settings): void
    {
        $this->nodesSettings[$nodeName] = $settings;
        $this->nodesPatterns[$nodeName] = $pattern;
        $this->patternsNodes[$pattern][] = $nodeName;
    }

    public function isNodeDemand(string $pattern): bool
    {
        return isset($this->patternsNodes[$pattern]);
    }
}
