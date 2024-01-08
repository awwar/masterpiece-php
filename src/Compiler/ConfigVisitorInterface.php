<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;

interface ConfigVisitorInterface
{
    public function persistNodePatternOption(string $flowName, string $nodeAlias, string $nodeAddon, string $nodePattern, array $nodeOption): void;

    public function isNodeDemand(string $nodeAddon, string $nodePattern): bool;

    public function getNodeOptions(string $nodeAddon, string $nodePattern): iterable;

    public function scoutAddonNode(string $nodeAddon, NodePattern $pattern): void;
}
