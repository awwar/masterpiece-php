<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Node\NodeInput;
use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class AddOnCompiler
{
    public function prefetch(AddOnInterface $addOn, ConfigVisitorInterface $configVisitor)
    {
    }

    public function compile(
        AddOnInterface $addOn,
        ConfigVisitorInterface $configVisitor,
        ClassVisitorInterface $classCreator
    ): void {
        $visitor = new AddOnCompileVisitor();

        $addonName = $addOn->getName();

        $addOn->compile($visitor);

        foreach ($visitor->getNodes() as $node) {
            $fullName = sprintf('%s_%s', $addonName, $node->getName());

            if ($configVisitor->isNodeDemand($fullName) === false) {
                continue;
            }

            // ToDo: code gen - we really need it
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

            $classCreator->createClass($fullName, $code);
        }
    }
}
