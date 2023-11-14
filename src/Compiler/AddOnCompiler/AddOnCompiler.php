<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
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

        foreach ($visitor->getNodes() as $nodeName => $node) {
            $arguments = "";

            foreach ($node->getInput() as $value) {
                $arguments .= "\$$value, ";
            }

            $body = $node->getBody();

            $code = <<<PHP
public static function execute($arguments): mixed
{
    $body
}
PHP;

            $classCreator->createClass($addonName, $nodeName, $code);
        }
    }
}
