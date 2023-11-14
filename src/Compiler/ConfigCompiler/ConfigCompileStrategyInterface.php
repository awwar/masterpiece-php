<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
interface ConfigCompileStrategyInterface
{
    public function getConfigName(): string;

    public function compile(): string;
}
