<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\FragmentCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\Util\ExecuteMethodName;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class FlowCompileStrategy implements ConfigCompileStrategyInterface
{
    public function getConfigName(): string
    {
        return 'flow';
    }

    public function prefetch(string $name, array $params, ConfigVisitorInterface $visitor): void
    {
        foreach ($params['nodes'] as $alias => $nodeSettings) {
            $visitor->persistNodePatternOption(
                flowName: $name,
                nodeAlias: $alias,
                nodeAddon: $nodeSettings['node']['addon'],
                nodePattern: $nodeSettings['node']['pattern'],
                nodeOption: $nodeSettings['option']
            );
        }
    }

    public function compile(string $name, array $params, AddOnCompileVisitorInterface $visitor): void
    {
        $input = NodeInputSet::create();
        $output = new NodeOutput(name: 'value', type: 'void');

        foreach ($params['input'] as $inputSettings) {
            $input->push(
                new NodeInput(name: $inputSettings['name'], type: $inputSettings['contract'])
            );
        }

        foreach ($params['output'] as $outputSettings) {
            $output = new NodeOutput(name: $outputSettings['name'], type: $outputSettings['contract']);
        }

        $node = new NodePattern(
            name: $name,
            input: $input,
            output: $output,
            nodeBodyCompileCallback: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $this->generateFunctionBody(
                $methodBodyGenerator,
                $name,
                $params,
                $visitor
            ),
            options: []
        );
        $visitor->setNode($node);
    }

    private function generateFunctionBody(
        MethodBodyGeneratorInterface $methodBodyGenerator,
        string $nodeName,
        array $params,
        AddOnCompileVisitorInterface $visitor
    ): void {
        $stack = [];

        foreach ($params['map'] as $socket => $translations) {
            $stack[$socket] = $socket;

            foreach ($translations as $translation) {
                $stack[$translation['socket']] = $translation['socket'];
            }
        }

        foreach ($stack as $socketName) {
            $socket = $params['sockets'][$socketName];
            $nodeAlias = $socket['node_alias'];

            $args = [];

            foreach ($socket['input'] ?? [] as $inputSettings) {
                $args[] = $inputSettings['variable'] ?? $inputSettings['node_alias'];
            }

            $nodeSettings = $params['nodes'][$nodeAlias]['node'];

            $node = $visitor->getNode($nodeSettings['pattern']);

            $nodeFullName = new NodeName(addonName: $nodeSettings['addon'], nodeName: $nodeSettings['pattern']);
            $methodName = new ExecuteMethodName(flowName: $nodeName, nodeAlias: $socket['node_alias']);

            $fragmentCompileContext = new FragmentCompileContext(
                methodBodyGenerator: $methodBodyGenerator,
                socketName: $socketName,
                args: $args,
                nodeName: $nodeFullName,
                functionName: $methodName
            );

            $node->compileNodeFragment($fragmentCompileContext);

            $methodBodyGenerator->newLineAndTab();
        }
    }
}
