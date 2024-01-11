<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ConfigVisitorInterface
{
    public function persistNodeTemplateOption(string $nodeName, string $nodeAlias, string $nodeAddon, string $nodeTemplate, array $nodeOption): void;

    public function isNodeDemand(string $nodeAddon, string $nodeTemplate): bool;

    public function getNodeOptions(string $nodeAddon, string $nodeTemplate): iterable;
}
