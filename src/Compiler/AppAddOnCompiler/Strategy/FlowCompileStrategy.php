<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\Strategy;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\FlowSubcompiler;
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

        $params['sockets']['start'] = [
            'transition' => [
                [
                    'condition' => true,
                    'socket'    => key($params['sockets']),
                ],
            ],
        ];

        $node = new NodePattern(
            addonName: 'app',
            name: $name,
            input: $input,
            output: $output,
            nodeBodyCompileCallback: function (BodyCompileContext $bodyCompileContext) use ($params, $name) {
                $subcompiler = new FlowSubcompiler($params, $name, $bodyCompileContext->getNodePatternObtain());

                $subcompiler->subcompileSocketCondition($bodyCompileContext->getMethodBodyGenerator(), 'start', 0);
            },
            options: []
        );
        $visitor->setNodePattern($node);
    }
}
