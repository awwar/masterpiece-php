<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class FlowCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'flow';
    }

    public function compile(array $params): string
    {
        return ""; //ToDo: flow compile logic
    }
}
