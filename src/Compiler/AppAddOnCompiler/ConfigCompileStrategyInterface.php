<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
interface ConfigCompileStrategyInterface
{
    public function getConfigName(): string;

    public function prefetch(array $params, ConfigVisitorInterface $visitor): void;

    public function compile(array $params, AddOnCompileVisitorInterface $visitor): void;
}
