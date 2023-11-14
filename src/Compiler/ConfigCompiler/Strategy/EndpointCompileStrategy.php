<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class EndpointCompileStrategy implements ConfigCompileStrategyInterface
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
