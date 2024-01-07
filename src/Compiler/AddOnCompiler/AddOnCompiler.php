<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGenerator;
use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\Util\ContractName;
use Awwar\MasterpiecePhp\Compiler\Util\ExecuteMethodName;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
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

            $nodeFullName = new NodeName(addonName: $addonName, nodeName: $node->getName());

            $classGenerator = new ClassGenerator(name: $nodeFullName);
            $classGenerator
                ->setNamespace('Awwar\MasterpiecePhp\App')
                ->addComment('Addon: ' . $addonName)
                ->addComment('Node: ' . $node->getName());

            $outputType = $node->getOutput()->getType();

            $options = $configVisitor->getNodeOptions($addonName, $node->getName());

            foreach ($options as $option) {
                $methodName = new ExecuteMethodName(flowName: $option['flow_name'], nodeAlias: $option['node_alias']);

                $method = $classGenerator
                    ->addMethod($methodName)
                    ->makeStatic()
                    ->addComment('Flow: ' . $option['flow_name'])
                    ->addComment('Alias: ' . $option['node_alias'])
                    ->setReturnType($outputType);

                $node->compileBody($method->getBodyGenerator(), $option['settings']);

                foreach ($node->getInput() as $input) {
                    $method->addArgument(name: $input->getName(), type: $input->getType());
                }
            }

            $classVisitor->createClass($nodeFullName, $classGenerator->generate());
        }

        foreach ($visitor->getContracts() as $contract) {
            $contractFullName = new ContractName(addonName: $addonName, contractName: $contract->getName());

            $classGenerator = new ClassGenerator(name: $contractFullName);
            $classGenerator->setNamespace('Awwar\MasterpiecePhp\App')
                ->addComment('Addon: ' . $addonName)
                ->addComment('Contract: ' . $contract->getName());

            $classGenerator->addProperty('value', 'int', '');

            $classGenerator
                ->addMethod('__construct')
                ->addArgument('value', 'int')
                ->getBodyGenerator()
                ->variable('this')->objectAccess()->raw('value')->assign()->variable('value')->semicolon()
                ->end();

            $classGenerator
                ->addMethod('getValue')
                ->setReturnType('int')
                ->getBodyGenerator()
                ->return()->variable('this')->objectAccess()->raw('value')->semicolon()
                ->end();

            foreach ($contract->getCastFrom() as $fromType => $nodeCallable) {
                [$class, $methodName] = $nodeCallable;

                $classGenerator
                    ->addMethod('cast_from_' . $fromType)
                    ->setReturnType('self')
                    ->makeStatic()
                    ->addArgument('value', $fromType)
                    ->getBodyGenerator()
                    ->return()->constant('\\' . $class)->staticAccess()->functionCall($methodName)
                        ->addArgumentAsVariable('value')
                    ->end()
                    ->semicolon()
                    ->end();
            }

            $classVisitor->createClass($contractFullName, $classGenerator->generate());
        }
    }
}
