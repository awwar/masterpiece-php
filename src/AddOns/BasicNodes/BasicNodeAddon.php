<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

class BasicNodeAddon implements AddOnInterface
{
    public function getName(): string
    {
        return 'basic_node';
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        $addition = new AddOnNode(
            name: 'addition',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->variable('a')->code('+')->variable('b')->semicolon()
        );
        $addOnCompileVisitor->setNode($addition);
        $subtraction = new AddOnNode(
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->variable('a')->code('-')->variable('b')->semicolon()
        );
        $addOnCompileVisitor->setNode($subtraction);
        $division = new AddOnNode(
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->variable('a')->code('/')->variable('b')->semicolon()
        );
        $addOnCompileVisitor->setNode($division);
        $multiplication = new AddOnNode(
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->variable('a')->code('*')->variable('b')->semicolon()
        );
        $addOnCompileVisitor->setNode($multiplication);
        $power = new AddOnNode(
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: 'int'))
                ->push(new NodeInput(name: 'exponent', type: 'int')),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')->end()->semicolon()
        );
        $addOnCompileVisitor->setNode($power);

        //ToDo: this node will compile with settings
        $number = new AddOnNode(
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: 'int'),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator, array $options) => $methodBodyGenerator
                ->return()
                ->statement($options['value']),
            options: []
        );
        $addOnCompileVisitor->setNode($number);
//
//        $if = new AddOnStructure(
//            name: 'if',
//            body: 'if (true) {
//
//            }'
//        );
//        $addOnCompileVisitor->setStructure($if);
    }
}
