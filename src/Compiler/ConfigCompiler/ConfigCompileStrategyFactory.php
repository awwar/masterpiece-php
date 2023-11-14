<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Container\Attributes\ServicesIterator;
use RuntimeException;

#[ForDependencyInjection]
class ConfigCompileStrategyFactory
{
    /** @param ConfigCompileStrategyInterface[] $strategies */
    public function __construct(
        #[ServicesIterator(instanceOf: ConfigCompileStrategyInterface::class)]
        private iterable $strategies
    ) {
    }

    public function create(string $name): ConfigCompileStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getConfigName() === $name) {
                return $strategy;
            }
        }

        throw new RuntimeException("Strategy $name not found");
    }
}
