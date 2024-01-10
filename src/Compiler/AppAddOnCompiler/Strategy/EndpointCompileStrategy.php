<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext\EndpointBodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointPattern;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Config\ExecuteMethodName;
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
        $node = $params['flow'];

        $configVisitor->persistNodePatternOption(
            flowName: $node->getNodePatternName(),
            nodeAlias: $node->getNodePatternName(),
            nodeAddon: $node->getAddonName(),
            nodePattern: $node->getNodePatternName(),
            nodeOption: []
        );

        $endpoint = new EndpointPattern(
            addonName: 'app',
            name: $name,
            nodeBodyCompileCallback: function (EndpointBodyCompileContext $context) use ($params) {
                /** @var NodeFullName $nodeFullName */
                $nodeFullName = $params['flow'];

                $executeMethodName = new ExecuteMethodName($nodeFullName->getNodePatternName(), $nodeFullName->getNodePatternName());

                $method = $context->getClassGenerator()->addMethod('execute')->makeStatic();

                $nodePattern = $context->getNodePatternObtainer()->getNodePattern((string) $nodeFullName);

                foreach ($nodePattern->getInput() as $input) {
                    $method->addParameter($input->getName(), $input->getType());
                }

                if ($nodePattern->getOutput()->isHasOutput()) {
                    $method->setReturnType($nodePattern->getOutput()->getType());
                } else {
                    $method->setReturnType('void');
                }

                $arguments = $method->getBodyGenerator()->return()->constant((string) $nodeFullName)->staticAccess()->functionCall(
                    $executeMethodName
                );

                foreach ($nodePattern->getInput() as $input) {
                    $arguments->addArgumentAsVariable($input->getName());
                }

                $arguments->end()->semicolon();
            }
        );

        $addOnCompileVisitor->setEndpointPattern($endpoint);
    }
}
