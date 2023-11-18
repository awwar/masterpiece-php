<?php

namespace Awwar\MasterpiecePhp\Compiler;

use RuntimeException;

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

    public function getNodeSettings(string $pattern): iterable
    {
        foreach ($this->patternsNodes[$pattern] as $nodeName) {
            yield $nodeName => $this->nodesSettings[$nodeName]
                ?? throw new RuntimeException(sprintf('No settings set for %s node', $nodeName));
        }
    }
}
