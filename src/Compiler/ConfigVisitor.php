<?php

namespace Awwar\MasterpiecePhp\Compiler;

class ConfigVisitor implements ConfigVisitorInterface
{
    private array $nodesSettings = [];

    public function persistNodePatternOption(
        string $flowName,
        string $nodeAlias,
        string $nodeAddon,
        string $nodePattern,
        array $nodeOption
    ): void {
        $this->nodesSettings[$nodeAddon][$nodePattern][$flowName][$nodeAlias] = $nodeOption;
    }

    public function isNodeDemand(string $nodeAddon, string $nodePattern): bool
    {
        return isset($this->nodesSettings[$nodeAddon][$nodePattern]);
    }

    public function getNodeOptions(string $nodeAddon, string $nodePattern): iterable
    {
        foreach ($this->nodesSettings[$nodeAddon][$nodePattern] as $flowName => $aliasToSettings) {
            foreach ($aliasToSettings as $nodeAlias => $nodeSettings) {
                yield [
                    'flow_name'  => $flowName,
                    'node_alias' => $nodeAlias,
                    'settings'   => $nodeSettings,
                ];
            }
        }
    }
}
