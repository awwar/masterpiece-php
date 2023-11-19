<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
interface ConfigCompileStrategyInterface
{
    public function getConfigName(): string;

    public function prefetch(string $name, array $params, ConfigVisitorInterface $visitor): void;

    public function compile(string $name, array $params, AddOnCompileVisitorInterface $visitor): void;
}
