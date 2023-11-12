<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompilerInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class ContractCompileStrategy implements ConfigCompilerInterface
{
    public function getConfigName(): string
    {
        return 'contract';
    }

    public function compile(): string
    {
        return ""; //ToDo: contract compile logic
    }
}