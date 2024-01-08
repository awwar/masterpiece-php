<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\AddOns\App\AppAddon;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGenerator;
use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompiler;
use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompileVisitor;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyFactory;
use Awwar\MasterpiecePhp\Compiler\Util\ContractName;
use Awwar\MasterpiecePhp\Compiler\Util\ExecuteMethodName;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;

#[ForDependencyInjection]
class Compiler
{
    public function __construct(
        private Filesystem $filesystem,
        private AddOnCompiler $addOnCompiler,
        private ConfigCompileStrategyFactory $factory
    ) {
    }

    public function compile(CompileContext $compileContext): void
    {
        // 1 scout configs
        // 2 scout addons
        // 3 build app addon
        // 4 compile addons
        $configVisitor = new ConfigVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $this
                ->factory
                ->create($config->getType())
                ->prefetch($config->getName(), $config->getParams(), $configVisitor);
        }

        $appAddonVisitor = new AddOnCompileVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $this
                ->factory
                ->create($config->getType())
                ->compile($config->getName(), $config->getParams(), $appAddonVisitor);
        }

        $compileContext->addAddOn(new AppAddon($appAddonVisitor));

        $classVisitor = new ClassVisitor();

        $visitor = new AddOnCompileVisitor();

        foreach ($compileContext->getAddOns() as $addOn) {
            $addOn->compile($visitor);
        }

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

                $compileContext = new BodyCompileContext($method->getBodyGenerator(), $option['settings']);

                $node->compileNodeBody($compileContext);

                if ($compileContext->isSkip()) {
                    continue;
                }

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

        $this->addOnCompiler->compile($addOn, $configVisitor, $classVisitor);

        $path = $compileContext->getGenerationPath();

        $this->filesystem->createDirectory($path);

        foreach ($classVisitor->getClasses() as $className => $content) {
            $filepath = sprintf('%s/%s.php', $path, $className);

            $this->filesystem->createFile($filepath, $content);
        }
    }
}
