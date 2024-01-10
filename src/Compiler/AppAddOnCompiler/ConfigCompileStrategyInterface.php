<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
interface ConfigCompileStrategyInterface
{
    public function getConfigName(): string;

    public function compile(
        string $name,
        array $params,
        AddOnCompileVisitorInterface $addOnCompileVisitor,
        ConfigVisitorInterface $configVisitor
    ): void;
}
