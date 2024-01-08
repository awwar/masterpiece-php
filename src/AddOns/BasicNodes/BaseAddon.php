<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\FragmentCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;
use Awwar\MasterpiecePhp\AddOns\BasicNodes\ContractCasters\MixedToIntegerNode;
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

        $addition = new NodePattern(
            name: 'addition',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new base_integer_contract(')
                ->variable('a')->objectAccess()->functionCall('getValue')->end()
                ->raw('+')
                ->variable('b')->objectAccess()->functionCall('getValue')->end()
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNode($addition);
        $subtraction = new NodePattern(
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('-')->variable('b')->raw(
                    ')'
                )->semicolon()
        );
        $addOnCompileVisitor->setNode($subtraction);
        $division = new NodePattern(
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('/')->variable('b')->raw(
                    ')'
                )->semicolon()
        );
        $addOnCompileVisitor->setNode($division);
        $multiplication = new NodePattern(
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()->raw('new base_integer_contract(')->variable('a')->raw('*')->variable('b')->raw(
                    ')'
                )->semicolon()
        );
        $addOnCompileVisitor->setNode($multiplication);
        $power = new NodePattern(
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'exponent', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new base_integer_contract(')
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')
                ->end()->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNode($power);

        $number = new NodePattern(
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: function (BodyCompileContext $context) {
                $context->getMethodBodyGenerator()
                    ->return()
                    ->raw('new base_integer_contract(')->raw($context->getOptions()['value'])->raw(')')->semicolon();
            },
            options: [
                'value',
            ]
        );
        $addOnCompileVisitor->setNode($number);

        $if = new NodePattern(
            name: 'output',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: null),
            nodeFragmentCompileCallback: function (FragmentCompileContext $context) {
                $firstInput = $context->getArgs()[0];

                $context->getMethodBodyGenerator()
                    ->return()->variable($firstInput)->semicolon();
            },
            options: [],
        );
        $addOnCompileVisitor->setNode($if);
    }
}
