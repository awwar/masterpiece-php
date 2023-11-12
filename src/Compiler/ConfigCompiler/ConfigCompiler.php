<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Config\ConfigInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class ConfigCompiler
{
    public function __construct(private StrategyFactory $factory)
    {
    }

    public function compile(ConfigInterface $config): iterable
    {
        $strategy = $this->factory->create($config->getEntityName());

        yield $strategy->compile(); //ToDo: config compile logic
    }
}
