<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\AddOns\BasicNodes\ContractCasters\MixedToIntegerNode;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Awwar\MasterpiecePhp\Contracts\basic_node_integer;

class BasicNodeAddon implements AddOnInterface
{
    public const NAME = 'basic_node';

    public function getName(): string
    {
        return self::NAME;
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        $integerContract = new Contract(name: 'integer');
        $integerContract->addCastFrom('mixed', MixedToIntegerNode::class, 'execute');

        $addOnCompileVisitor->setContract($integerContract);

        $addition = new AddOnNode(
            name: 'addition',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'basic_node_integer'))
                ->push(new NodeInput(name: 'b', type: 'basic_node_integer')),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()
                ->code('new c\\basic_node_integer(')
                    ->variable('a')->objectAccess()->functionCall('getValue')->end()
                    ->code('+')
                    ->variable('b')->objectAccess()->functionCall('getValue')->end()
                ->code(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNode($addition);
        $subtraction = new AddOnNode(
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'basic_node_integer'))
                ->push(new NodeInput(name: 'b', type: 'basic_node_integer')),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->code('new c\\basic_node_integer(')->variable('a')->code('-')->variable('b')->code(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($subtraction);
        $division = new AddOnNode(
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'basic_node_integer'))
                ->push(new NodeInput(name: 'b', type: 'basic_node_integer')),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->code('new c\\basic_node_integer(')->variable('a')->code('/')->variable('b')->code(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($division);
        $multiplication = new AddOnNode(
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'basic_node_integer'))
                ->push(new NodeInput(name: 'b', type: 'basic_node_integer')),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->code('new c\\basic_node_integer(')->variable('a')->code('*')->variable('b')->code(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($multiplication);
        $power = new AddOnNode(
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: 'basic_node_integer'))
                ->push(new NodeInput(name: 'exponent', type: 'basic_node_integer')),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()
                ->code('new c\\basic_node_integer(')
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')
                ->end()->code(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($power);

        $number = new AddOnNode(
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: 'basic_node_integer'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator, array $options) => $methodBodyGenerator
                ->return()
                ->code('new c\\basic_node_integer(')->code($options['value'])->code(')')->semicolon(),
            options: []
        );
        $addOnCompileVisitor->setNode($number);
    }
}
