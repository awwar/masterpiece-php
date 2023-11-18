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
            $fullName = sprintf('%s_%s', $addonName, $node->getName());

            if ($configVisitor->isNodeDemand($fullName) === false) {
                continue;
            }

            $classGenerator = new ClassGenerator(name: $fullName);
            $classGenerator->setNamespace('Awwar\MasterpiecePhp\Nodes');

            $outputType = $node->getOutput()->getType();

            $options = $configVisitor->getNodeSettings($fullName);

            foreach ($options as $alias => $option) {
                $body = $node->getBody($option);

                $method = $classGenerator
                    ->addMethod('execute_for_alias_' . $alias)
                    ->setReturnType($outputType)
                    ->setBody($body);

                foreach ($node->getInput() as $input) {
                    $method->addArgument(name: $input->getName(), type: $input->getType());
                }
            }

            $classVisitor->createClass($fullName, $classGenerator);
        }
    }
}
