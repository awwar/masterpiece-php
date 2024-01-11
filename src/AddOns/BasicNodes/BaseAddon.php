<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Contract\ContractTemplate;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext\EndpointBodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointTemplate;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\NodeFragmentCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\NodeBodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Node\NodeOutput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeTemplate;
use Awwar\MasterpiecePhp\AddOns\BasicNodes\ContractCasters\MixedToIntegerNode;
use Awwar\MasterpiecePhp\App\base_integer_contract;
use Awwar\MasterpiecePhp\Config\ContractName;
use Awwar\MasterpiecePhp\Config\ExecuteMethodName;
use Awwar\MasterpiecePhp\Config\NodeFullName;

class BaseAddon implements AddOnInterface
{
    public const NAME = 'base';

    public function getName(): string
    {
        return self::NAME;
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        $integerContract = new ContractTemplate(addonName: self::NAME, name: 'integer');
        $integerContract->addCastFrom('mixed', MixedToIntegerNode::class, 'execute');

        $addOnCompileVisitor->setContractTemplate($integerContract);

//        $mixedToInteger = new NodeTemplate(
//            addonName: self::NAME,
//            name: 'mixed_to_integer',
//            input: NodeInputSet::create()
//                ->push(new NodeInput(name: 'input', type: 'mixed')),
//            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
//            nodeBodyCompileCallback: function (NodeBodyCompileContext $context) {
//                $context->getMethodBodyGenerator()
//                    ->raw('if(false === ')->functionCall('is_numeric')->addArgumentAsVariable('input')->end()->raw(') {')->newLineAndTab()
//                    ->raw("throw new \RuntimeException('Unable to cast not numeric to integer')")->semicolon()->newLineAndTab()
//                    ->raw('}')->newLineAndTab()
//                    ->return()->raw('new base_integer_contract(value: (int) ')->variable('input')->raw(')')->semicolon();
//            }
//        );
//        $addOnCompileVisitor->setNodeTemplate($mixedToInteger);

        $addition = new NodeTemplate(
            addonName: self::NAME,
            name: 'addition',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (NodeBodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->objectAccess()->functionCall('getValue')->end()
                ->raw('+')
                ->variable('b')->objectAccess()->functionCall('getValue')->end()
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodeTemplate($addition);
        $subtraction = new NodeTemplate(
            addonName: self::NAME,
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (NodeBodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('-')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodeTemplate($subtraction);
        $division = new NodeTemplate(
            addonName: self::NAME,
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (NodeBodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('/')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodeTemplate($division);
        $multiplication = new NodeTemplate(
            addonName: self::NAME,
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'b', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (NodeBodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->variable('a')->raw('*')->variable('b')
                ->raw(')')
                ->semicolon()
        );
        $addOnCompileVisitor->setNodeTemplate($multiplication);
        $power = new NodeTemplate(
            addonName: self::NAME,
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: new ContractName('base', 'integer')))
                ->push(new NodeInput(name: 'exponent', type: new ContractName('base', 'integer'))),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeBodyCompileCallback: fn (NodeBodyCompileContext $context) => $context->getMethodBodyGenerator()
                ->return()
                ->raw('new ' . $integerContract->getFullName() . '(')
                ->functionCall('pow')->addArgumentAsVariable('num')->addArgumentAsVariable('exponent')
                ->end()->raw(')')->semicolon()
        );
        $addOnCompileVisitor->setNodeTemplate($power);

        $number = new NodeTemplate(
            addonName: self::NAME,
            name: 'number',
            input: NodeInputSet::create(),
            output: new NodeOutput(name: 'value', type: new ContractName('base', 'integer')),
            nodeFragmentCompileCallback: function (NodeFragmentCompileContext $context) use ($integerContract) {
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
        $addOnCompileVisitor->setNodeTemplate($number);

        $output = new NodeTemplate(
            addonName: self::NAME,
            name: 'output',
            input: NodeInputSet::create(),
            output: NodeOutput::noOutput(),
            nodeFragmentCompileCallback: function (NodeFragmentCompileContext $context) {
                $firstInput = $context->getArgs()[0];

                $context->getMethodBodyGenerator()
                    ->return()->variable($firstInput)->semicolon();
            },
            options: [],
        );
        $addOnCompileVisitor->setNodeTemplate($output);

        $if = new NodeTemplate(
            addonName: self::NAME,
            name: 'if',
            input: NodeInputSet::create(),
            output: NodeOutput::noOutput(),
            nodeFragmentCompileCallback: function (NodeFragmentCompileContext $context) {
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
            },
            options: [],
        );
        $addOnCompileVisitor->setNodeTemplate($if);

        $endpoint = new EndpointTemplate(
            addonName: self::NAME,
            name: 'wrap',
            nodeBodyCompileCallback: function (EndpointBodyCompileContext $context) {
                foreach ($context->getOptions() as $endpointName => $params) {
                    /** @var NodeFullName $nodeFullName */
                    $nodeFullName = $params['node'];

                    $executeMethodName = new ExecuteMethodName($nodeFullName->getNodeTemplateName(), $nodeFullName->getNodeTemplateName());

                    $method = $context->getClassGenerator()->addMethod('execute_for_' . $endpointName)->makeStatic();

                    $nodeTemplate = $context->getNodeTemplateObtainer()->getNodeTemplate((string) $nodeFullName);

                    foreach ($nodeTemplate->getInput() as $input) {
                        $method->addParameter($input->getName(), $input->getType());
                    }

                    if ($nodeTemplate->getOutput()->isHasOutput()) {
                        $method->setReturnType($nodeTemplate->getOutput()->getType());
                    } else {
                        $method->setReturnType('void');
                    }

                    $arguments = $method->getBodyGenerator()->return()->constant((string) $nodeFullName)->staticAccess()->functionCall(
                        $executeMethodName
                    );

                    foreach ($nodeTemplate->getInput() as $input) {
                        $arguments->addArgumentAsVariable($input->getName());
                    }

                    $arguments->end()->semicolon();
                }
            }
        );

        $addOnCompileVisitor->setEndpointTemplate($endpoint);
    }
}
