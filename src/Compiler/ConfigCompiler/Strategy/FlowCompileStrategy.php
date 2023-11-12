<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompilerInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class FlowCompileStrategy implements ConfigCompilerInterface
{
    public function getConfigName(): string
    {
        return 'flow';
    }

    public function compile(): string
    {
        return ""; //ToDo: flow compile logic
    }
}