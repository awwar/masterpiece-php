<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class EndpointCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'endpoint';
    }

    public function prefetch(string $name, array $params, ConfigVisitorInterface $visitor): void
    {
        $visitor->persistNodePatternOption(
            flowName: $params['flow'],
            nodeAlias: $params['flow'],
            nodeAddon: 'app',
            nodePattern: $params['flow'],
            nodeOption: []
        );
    }

    public function compile(string $name, array $params, AddOnCompileVisitorInterface $visitor): void
    {
    }
}
