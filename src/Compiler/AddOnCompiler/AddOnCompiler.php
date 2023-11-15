<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class AddOnCompiler
{
    public function compile(AddOnInterface $addOn, ClassVisitorInterface $classCreator): void
    {
        $visitor = new AddOnCompileVisitor();

        $addonName = $addOn->getName();

        $addOn->compile($visitor);

        foreach ($visitor->getNodes() as $node) {
            $arguments = $node
                ->getInput()
                ->reduce(
                    fn (NodeInput $input, string $argString) => $argString === ""
                        ? '$' . $input->getName()
                        : "$argString, \${$input->getName()}",
                    ""
                );

            $body = $node->getBody();

            $code = <<<PHP
public static function execute($arguments): mixed
{
    $body
}
PHP;

            $classCreator->createClass($addonName, $node->getName(), $code);
        }
    }
}
