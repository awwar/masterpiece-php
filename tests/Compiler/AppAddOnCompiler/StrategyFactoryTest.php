<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy\ContractCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy\EndpointCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy\FlowCompileStrategy;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyFactory;
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
        $factory = $this->container->get(ConfigCompileStrategyFactory::class);

        $strategy = $factory->create($strategyName);

        self::assertInstanceOf($className, $strategy);
    }
}
