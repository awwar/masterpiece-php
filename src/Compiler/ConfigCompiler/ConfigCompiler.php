<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Config\ConfigInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class ConfigCompiler
{
    public function __construct(private ConfigCompileStrategyFactory $factory)
    {
    }

    public function compile(ConfigInterface $config, ClassVisitorInterface $classVisitor): void
    {
        $strategy = $this->factory->create($config->getEntityName());

        $classVisitor->createClass('unknown', 'unknown', $strategy->compile()); //ToDo: config compile logic
    }
}
