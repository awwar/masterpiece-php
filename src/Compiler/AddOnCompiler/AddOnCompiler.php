<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGenerator;
use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class AddOnCompiler
{
    public function compile(
        AddOnInterface $addOn,
        ConfigVisitorInterface $configVisitor,
        ClassVisitorInterface $classVisitor
    ): void {
        $visitor = new AddOnCompileVisitor();

        $addonName = $addOn->getName();

        $addOn->compile($visitor);

        foreach ($visitor->getNodes() as $node) {
            if ($configVisitor->isNodeDemand($addonName, $node->getName()) === false) {
                continue;
            }

            $nodeFullName = sprintf('%s_%s', $addonName, $node->getName());

            $classGenerator = new ClassGenerator(name: $nodeFullName);
            $classGenerator
                ->setNamespace('Awwar\MasterpiecePhp\Nodes')
                ->addComment('Addon: ' . $addonName)
                ->addComment('Node: ' . $node->getName());

            $outputType = $node->getOutput()->getType();

            $options = $configVisitor->getNodeOptions($addonName, $node->getName());

            foreach ($options as $option) {
                $body = $node->getBody($option['settings']);

                $methodName = sha1(sprintf('%s_%s', $option['flow_name'], $option['node_alias']));

                $method = $classGenerator
                    ->addMethod('execute_' . $methodName)
                    ->addComment('Flow: ' . $option['flow_name'])
                    ->addComment('Alias: ' . $option['node_alias'])
                    ->setReturnType($outputType)
                    ->setBody($body);

                foreach ($node->getInput() as $input) {
                    $method->addArgument(name: $input->getName(), type: $input->getType());
                }
            }

            $classVisitor->createClass($nodeFullName, $classGenerator->generate());
        }
    }
}
