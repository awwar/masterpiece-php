<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ConfigVisitorInterface
{
    public function persistNodeTemplateOption(
        string $nodeName,
        string $nodeAlias,
        string $nodeAddon,
        string $nodeTemplate,
        array $nodeOption
    ): void;

    public function isNodeDemand(string $nodeAddon, string $nodeTemplate): bool;

    public function getNodeOptions(string $nodeAddon, string $nodeTemplate): iterable;

    public function persistEndpointOption(
        string $endpointPatternFullName,
        string $endpointName,
        array $endpointOption
    ): void;

    public function getEndpointOptions(string $endpointPatternFullName): array;
}
