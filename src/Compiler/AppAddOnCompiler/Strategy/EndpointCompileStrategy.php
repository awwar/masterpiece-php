<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Config\NodeFullName;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class EndpointCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'endpoint';
    }

    public function compile(
        string $name,
        array $params,
        AddOnCompileVisitorInterface $addOnCompileVisitor,
        ConfigVisitorInterface $configVisitor
    ): void {
        /** @var NodeFullName $node */
        $node = $params['node'];

        $configVisitor->persistNodeTemplateOption(
            nodeName: $node->getNodeTemplateName(),
            nodeAlias: $node->getNodeTemplateName(),
            nodeAddon: $node->getAddonName(),
            nodeTemplate: $node->getNodeTemplateName(),
            nodeOption: []
        );

        $configVisitor->persistEndpointOption(
            endpointPatternFullName: (string) $params['template'],
            endpointName: $name,
            endpointOption: $params
        );
    }
}
