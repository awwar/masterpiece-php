<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyInterface;
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

    public function compile(array $params, ClassVisitorInterface $visitor): string
    {
        return ""; //ToDo: flow compile logic
    }

    public function isDemand(string $name, ConfigVisitorInterface $visitor): bool
    {
        return $visitor->isNodeDemand($name);
    }
}
