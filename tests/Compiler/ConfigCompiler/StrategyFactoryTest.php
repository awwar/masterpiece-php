<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler\ConfigCompiler;

use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy\ContractCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy\EndpointCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\Strategy\FlowCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\StrategyFactory;
use Awwar\MasterpiecePhp\Tests\CaseWithContainer;

class StrategyFactoryTest extends CaseWithContainer
{
    public static function configCompileStrategiesDataProvider(): array
    {
        return [
            ['contract', ContractCompileStrategy::class],
            ['endpoint', EndpointCompileStrategy::class],
            ['flow', FlowCompileStrategy::class],
        ];
    }

    /**
     * @dataProvider configCompileStrategiesDataProvider()
     * @param string $strategyName
     * @param string $className
     * @return void
     */
    public function testCreateWhenOk(string $strategyName, string $className): void
    {
        $factory = $this->container->get(StrategyFactory::class);

        $strategy = $factory->create($strategyName);

        self::assertInstanceOf($className, $strategy);
    }
}
