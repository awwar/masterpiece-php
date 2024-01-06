<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
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

        $node = new AddOnNode(
            name: $name,
            input: $input,
            output: $output,
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $this->generateFunctionBody(
                $methodBodyGenerator,
                $name,
                $params
            ),
            options: []
        );
        $visitor->setNode($node);
    }

    private function generateFunctionBody(
        MethodBodyGeneratorInterface $methodBodyGenerator,
        string $nodeName,
        array $params
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

            if ($nodeAlias === 'output') {
                $methodBodyGenerator
                    ->newLineAndTab()
                    ->return()->variable($args[0])->semicolon();
            } else {
                $nodeSettings = $params['nodes'][$nodeAlias]['node'];
                $nodeFullName = sprintf('%s_%s', $nodeSettings['addon'], $nodeSettings['pattern']);
                $methodName = 'execute_' . sha1(sprintf('%s_%s', $nodeName, $socket['node_alias']));

                $methodCall = $methodBodyGenerator->variable($socketName)->assign()->constant($nodeFullName)->staticAccess()->functionCall($methodName);

                foreach ($args as $arg) {
                    $methodCall->addArgumentAsVariable($arg);
                }

                $methodCall->end()->semicolon();
            }

            $methodBodyGenerator->newLineAndTab();
        }
    }
}
