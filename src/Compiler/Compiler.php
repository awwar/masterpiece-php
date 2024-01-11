<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext\EndpointBodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\NodeBodyCompileContext;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGenerator;
use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompileVisitor;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyFactory;
use Awwar\MasterpiecePhp\Config\ExecuteMethodName;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;

#[ForDependencyInjection]
class Compiler
{
    public function __construct(
        private Filesystem $filesystem,
        private ConfigCompileStrategyFactory $factory
    ) {
    }

    public function compile(CompileContext $compileContext): void
    {
        $configVisitor = new ConfigVisitor();

        $addOnCompileVisitor = new AddOnCompileVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $this
                ->factory
                ->create($config->getType())
                ->compile($config->getName(), $config->getParams(), $addOnCompileVisitor, $configVisitor);
        }

        foreach ($compileContext->getAddOns() as $addOn) {
            $addOn->compile($addOnCompileVisitor);
        }

        $classVisitor = new ClassVisitor();

        foreach ($addOnCompileVisitor->getNodeTemplates() as $nodeTemplate) {
            if ($configVisitor->isNodeDemand($nodeTemplate->getAddonName(), $nodeTemplate->getName()) === false) {
                continue;
            }

            $nodeFullName = $nodeTemplate->getFullName();

            $classGenerator = new ClassGenerator(name: $nodeFullName);
            $classGenerator
                ->setNamespace('Awwar\MasterpiecePhp\App')
                ->addComment('Addon: ' . $nodeTemplate->getAddonName())
                ->addComment('NodeTemplate: ' . $nodeTemplate->getName());

            $outputType = $nodeTemplate->getOutput()->getType();

            if ($nodeTemplate->getOutput()->isHasOutput() === false) {
                $outputType = 'void';
            }

            $options = $configVisitor->getNodeOptions($nodeTemplate->getAddonName(), $nodeTemplate->getName());

            $methodsCount = 0;

            foreach ($options as $option) {
                $methodName = new ExecuteMethodName(nodeName: $option['node_name'], nodeAlias: $option['node_alias']);

                $method = $classGenerator
                    ->addMethod($methodName)
                    ->makeStatic()
                    ->addComment('Alias: ' . $option['node_alias'])
                    ->addComment('NodeTemplate: ' . $option['node_name'])
                    ->setReturnType($outputType);

                $bodyCompileContext = new NodeBodyCompileContext(
                    $method->getBodyGenerator(),
                    $option['settings'],
                    $addOnCompileVisitor
                );

                $nodeTemplate->compileNodeBody($bodyCompileContext);

                if ($bodyCompileContext->isSkip()) {
                    continue;
                }

                $methodsCount++;

                foreach ($nodeTemplate->getInput() as $input) {
                    $method->addParameter(name: $input->getName(), type: $input->getType());
                }
            }

            if ($methodsCount === 0) {
                continue;
            }

            $classVisitor->createClass($nodeFullName, $classGenerator->generate());
        }

        foreach ($addOnCompileVisitor->getContractTemplates() as $contract) {
            $contractFullName = $contract->getFullName();

            $classGenerator = new ClassGenerator(name: $contractFullName);
            $classGenerator->setNamespace('Awwar\MasterpiecePhp\App')
                ->addComment('Addon: ' . $contract->getAddonName())
                ->addComment('ContractTemplate: ' . $contract->getName());

            $classGenerator->addProperty('value', 'int', '');

            $classGenerator
                ->addMethod('__construct')
                ->addParameter('value', 'int')
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
                    ->addParameter('value', $fromType)
                    ->getBodyGenerator()
                    ->return()->constant('\\' . $class)->staticAccess()->functionCall($methodName)
                    ->addArgumentAsVariable('value')
                    ->end()
                    ->semicolon()
                    ->end();
            }

            $classVisitor->createClass($contractFullName, $classGenerator->generate());
        }

        foreach ($addOnCompileVisitor->getEndpointTemplates() as $endpointTemplate) {
            $endpointFullName = $endpointTemplate->getFullName();

            $classGenerator = new ClassGenerator(name: $endpointFullName);
            $classGenerator->setNamespace('Awwar\MasterpiecePhp\App')
                ->addComment('Addon: ' . $endpointTemplate->getAddonName())
                ->addComment('EndpointTemplate: ' . $endpointTemplate->getName());

            $options = $configVisitor->getEndpointOptions($endpointFullName);

            foreach ($options as $endpointName => $params) {
                $context = new EndpointBodyCompileContext(
                    $classGenerator,
                    $addOnCompileVisitor,
                    $endpointName,
                    $params
                );

                $endpointTemplate->compileEndpointBody($context);
            }

            $classVisitor->createClass($endpointFullName, $classGenerator->generate());
        }

        $path = $compileContext->getGenerationPath();

        $this->filesystem->createDirectory($path);

        foreach ($classVisitor->getClasses() as $className => $content) {
            $filepath = sprintf('%s/%s.php', $path, $className);

            $this->filesystem->createFile($filepath, $content);
        }
    }
}
