<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class ContractCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'contract';
    }

    public function prefetch(array $params, ConfigVisitorInterface $visitor): void
    {
    }

    public function compile(array $params, ClassVisitorInterface $visitor): string
    {
        return ""; //ToDo: contract compile logic
    }

    public function isDemand(string $name, ConfigVisitorInterface $visitor): bool
    {
    }
}
