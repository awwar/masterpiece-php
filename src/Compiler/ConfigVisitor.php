<?php

namespace Awwar\MasterpiecePhp\Compiler;

class ConfigVisitor implements ConfigVisitorInterface
{
    private array $nodesSettings = [];

    private array $endpointOptions = [];

    public function persistNodeTemplateOption(
        string $nodeName,
        string $nodeAlias,
        string $nodeAddon,
        string $nodeTemplate,
        array $nodeOption
    ): void {
        $this->nodesSettings[$nodeAddon][$nodeTemplate][$nodeName][$nodeAlias] = $nodeOption;
    }

    public function isNodeDemand(string $nodeAddon, string $nodeTemplate): bool
    {
        return isset($this->nodesSettings[$nodeAddon][$nodeTemplate]);
    }

    public function getNodeOptions(string $nodeAddon, string $nodeTemplate): iterable
    {
        foreach ($this->nodesSettings[$nodeAddon][$nodeTemplate] as $nodeName => $aliasToSettings) {
            foreach ($aliasToSettings as $nodeAlias => $nodeSettings) {
                yield [
                    'node_name'  => $nodeName,
                    'node_alias' => $nodeAlias,
                    'settings'   => $nodeSettings,
                ];
            }
        }
    }

    public function persistEndpointOption(string $endpointPatternFullName, string $endpointName, array $endpointOption): void
    {
        $this->endpointOptions[$endpointPatternFullName][$endpointName] = $endpointOption;
    }

    public function getEndpointOptions(string $endpointPatternFullName): array
    {
        return $this->endpointOptions[$endpointPatternFullName];
    }
}
