<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\NodeExecutable;

class BasicNodeAddon implements AddOnInterface
{
    public function getName(): string
    {
        return 'basic_node';
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        $executable = new NodeExecutable(
            input: ['a', 'b'],
            body: <<<PHP
return \$a + \$b;
PHP
        );

        $addOnCompileVisitor->createNode('sum', $executable);
    }
}
