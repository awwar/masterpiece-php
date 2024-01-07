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
use Awwar\MasterpiecePhp\Compiler\Util\ContractName;

class BaseAddon implements AddOnInterface
{
    public const NAME = 'base';

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
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()
                ->raw('new base_integer_contract(')
                    ->variable('a')->objectAccess()->functionCall('getValue')->end()
                    ->raw('+')
                    ->variable('b')->objectAccess()->functionCall('getValue')->end()
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNode($addition);
        $subtraction = new AddOnNode(
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('-')->variable('b')->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($subtraction);
        $division = new AddOnNode(
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('/')->variable('b')->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($division);
        $multiplication = new AddOnNode(
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('*')->variable('b')->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($multiplication);
        $power = new AddOnNode(
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'exponent', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator) => $methodBodyGenerator
                ->return()
                ->raw('new base_integer_contract(')
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')
                ->end()->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($power);

        $number = new AddOnNode(
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            body: fn (MethodBodyGeneratorInterface $methodBodyGenerator, array $options) => $methodBodyGenerator
                ->return()
                ->raw('new base_integer_contract(')->raw($options['value'])->raw(')')->semicolon(),
            options: []
        );
        $addOnCompileVisitor->setNode($number);
    }
}
