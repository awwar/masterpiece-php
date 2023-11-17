<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Container\Attributes\ServicesIterator;
use RuntimeException;

#[ForDependencyInjection]
class ConfigCompileStrategyFactory
{
    /** @param ConfigCompileStrategyInterface[] $strategies */
    private array $strategies = [];

    /** @param ConfigCompileStrategyInterface[] $strategies */
    public function __construct(
        #[ServicesIterator(instanceOf: ConfigCompileStrategyInterface::class)]
        iterable $strategies
    ) {
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->getConfigName()] = $strategy;
        }
    }

    public function create(string $name): ConfigCompileStrategyInterface
    {
        return $this->strategies[$name] ?? throw new RuntimeException("Strategy $name not found");
    }
}
