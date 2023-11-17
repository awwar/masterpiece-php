<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInputSet;
use Awwar\MasterpiecePhp\AddOn\Structure\AddOnStructure;

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
            body: 'return $a + $b;'
        );
        $addOnCompileVisitor->setNode($addition);
        $subtraction = new AddOnNode(
            name: 'subtraction',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            body: 'return $a - $b;'
        );
        $addOnCompileVisitor->setNode($subtraction);
        $division = new AddOnNode(
            name: 'division',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            body: 'return $a / $b;'
        );
        $addOnCompileVisitor->setNode($division);
        $multiplication = new AddOnNode(
            name: 'multiplication',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'a', type: 'int'))
                ->push(new NodeInput(name: 'b', type: 'int')),
            body: 'return $a * $b;'
        );
        $addOnCompileVisitor->setNode($multiplication);
        $power = new AddOnNode(
            name: 'power',
            input: NodeInputSet::create()
                ->push(new NodeInput(name: 'num', type: 'int'))
                ->push(new NodeInput(name: 'exponent', type: 'int')),
            body: 'return pow($num, $exponent);'
        );
        $addOnCompileVisitor->setNode($power);

        //ToDo: this node will compile with settings
        $number = new AddOnNode(
            name: 'number',
            input: NodeInputSet::create(),
            body: 'return {{value}};'
        );
        $addOnCompileVisitor->setNode($number);

        $if = new AddOnStructure(
            name: 'if',
            body: 'if (true) {
            
            }'
        );
        $addOnCompileVisitor->setStructure($if);
    }
}
