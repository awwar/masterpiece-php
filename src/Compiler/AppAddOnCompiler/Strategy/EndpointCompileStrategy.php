<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext\EndpointBodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointTemplate;
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
        $node = $params['node'];

        $configVisitor->persistNodeTemplateOption(
            nodeName: $node->getNodeTemplateName(),
            nodeAlias: $node->getNodeTemplateName(),
            nodeAddon: $node->getAddonName(),
            nodeTemplate: $node->getNodeTemplateName(),
            nodeOption: []
        );

        $endpoint = new EndpointTemplate(
            addonName: 'app',
            name: $name,
            nodeBodyCompileCallback: function (EndpointBodyCompileContext $context) use ($params) {
                /** @var NodeFullName $nodeFullName */
                $nodeFullName = $params['node'];

                $executeMethodName = new ExecuteMethodName($nodeFullName->getNodeTemplateName(), $nodeFullName->getNodeTemplateName());

                $method = $context->getClassGenerator()->addMethod('execute')->makeStatic();

                $nodeTemplate = $context->getNodeTemplateObtainer()->getNodeTemplate((string) $nodeFullName);

                foreach ($nodeTemplate->getInput() as $input) {
                    $method->addParameter($input->getName(), $input->getType());
                }

                if ($nodeTemplate->getOutput()->isHasOutput()) {
                    $method->setReturnType($nodeTemplate->getOutput()->getType());
                } else {
                    $method->setReturnType('void');
                }

                $arguments = $method->getBodyGenerator()->return()->constant((string) $nodeFullName)->staticAccess()->functionCall(
                    $executeMethodName
                );

                foreach ($nodeTemplate->getInput() as $input) {
                    $arguments->addArgumentAsVariable($input->getName());
                }

                $arguments->end()->semicolon();
            }
        );

        $addOnCompileVisitor->setEndpointTemplate($endpoint);
    }
}
