<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class EndpointCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'endpoint';
    }

    public function prefetch(array $params, ConfigVisitorInterface $visitor): void
    {
    }

    public function compile(array $params, ClassVisitorInterface $visitor): string
    {
        return ""; //ToDo: endpoint compile logic
    }

    public function isDemand(string $name, ConfigVisitorInterface $visitor): bool
    {
    }
}
