<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class FlowCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'flow';
    }

    public function prefetch(array $params, ConfigVisitorInterface $visitor): void
    {
        foreach ($params['nodes'] as $nodeName => $nodeSettings) {
            $visitor->persistNodePatternOption($nodeName, $nodeSettings['pattern'], $nodeSettings['option']);
        }
    }

    public function compile(array $params, AddOnCompileVisitorInterface $visitor): void
    {
    }
}
