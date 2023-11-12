<?php

namespace Awwar\MasterpiecePhp\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Container\Attributes\ServicesIterator;
use RuntimeException;

#[ForDependencyInjection]
class StrategyFactory
{
    /** @param ConfigCompilerInterface[] $strategies */
    public function __construct(
        #[ServicesIterator(instanceOf: ConfigCompilerInterface::class)]
        private iterable $strategies
    ) {
    }

    public function create(string $name): ConfigCompilerInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getConfigName() === $name) {
                return $strategy;
            }
        }

        throw new RuntimeException("Strategy $name not found");
    }
}
