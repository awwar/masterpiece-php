<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompilerInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class EndpointCompileStrategy implements ConfigCompilerInterface
{
    public function getConfigName(): string
    {
        return 'endpoint';
    }

    public function compile(): string
    {
        return ""; //ToDo: endpoint compile logic
    }
}