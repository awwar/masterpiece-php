<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\FlowFragmentCompileContext;
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
        $integerContract = new Contract(addonName: self::NAME, name: 'integer');
        $integerContract->addCastFrom('mixed', MixedToIntegerNode::class, 'execute');

        $addOnCompileVisitor->setContract($integerContract);

        $addition = new NodePattern(
            addonName: self::NAME,
            name: 'addition',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->objectAccess()->functionCall('getValue')->end()
                ->raw('+')
                ->variable('b')->objectAccess()->functionCall('getValue')->end()
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodePattern($addition);
        $subtraction = new NodePattern(
            addonName: self::NAME,
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('-')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodePattern($subtraction);
        $division = new NodePattern(
            addonName: self::NAME,
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('/')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodePattern($division);
        $multiplication = new NodePattern(
            addonName: self::NAME,
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('*')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodePattern($multiplication);
        $power = new NodePattern(
            addonName: self::NAME,
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'exponent', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (BodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')
                ->end()->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNodePattern($power);

        $number = new NodePattern(
            addonName: self::NAME,
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            flowFragmentCompileCallback: function (FlowFragmentCompileContext $context) use ($integerContract) {
                $context->getMethodBodyGenerator()
                    ->variable($context->getSocketName())
                    ->assign()
                    ->raw('new ' . $integerContract->getFullName() . '(')
                    ->constant($context->getOptions()['value'])
                    ->raw(')')
                    ->semicolon();
            },
            options: [
                'value',
            ]
        );
        $addOnCompileVisitor->setNodePattern($number);

        $output = new NodePattern(
            addonName: self::NAME,
            name: 'output',
            input: NodeInputSet::create(),
            output: NodeOutput::noOutput(),
            flowFragmentCompileCallback: function (FlowFragmentCompileContext $context) {
                $firstInput = $context->getArgs()[0];

                $context->getMethodBodyGenerator()
                    ->return()->variable($firstInput)->semicolon();
            },
            options: [],
        );
        $addOnCompileVisitor->setNodePattern($output);

        $if = new NodePattern(
            addonName: self::NAME,
            name: 'if',
            input: NodeInputSet::create(),
            output: NodeOutput::noOutput(),
            flowFragmentCompileCallback: function (FlowFragmentCompileContext $context) {
                $condition = $context->getOptions()['condition'];

                $replacement = [];
                foreach ($context->getArgs() as $i => $arg) {
                    $replacement["\$$i"] = "\${$arg}->getValue()";
                }
                $condition = strtr($condition, $replacement);

                $generator = $context->getMethodBodyGenerator();

                $generator->newLineAndTab()->raw("if ($condition) {")->newLineAndTab();

                $context->getSubcompile()->subcompileSocketCondition($generator, $context->getSocketName(), 0);

                $generator->newLineAndTab()->raw("} else {")->newLineAndTab();

                $context->getSubcompile()->subcompileSocketCondition($generator, $context->getSocketName(), 1);

                $generator->newLineAndTab()->raw("}");

                $generator->newLineAndTab();
            },
            options: [],
        );
        $addOnCompileVisitor->setNodePattern($if);
    }
}
