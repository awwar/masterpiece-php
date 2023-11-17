<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
interface ConfigCompileStrategyInterface
{
    public function getConfigName(): string;

    public function prefetch(array $params, ConfigVisitorInterface $visitor): void;

    public function compile(array $params, ClassVisitorInterface $visitor): string;

    public function isDemand(string $name, ConfigVisitorInterface $visitor): bool;
}
