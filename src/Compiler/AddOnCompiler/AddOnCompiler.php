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
                    function (NodeInput $input, string $argString) {
                        $argumentDeclaration = sprintf('%s $%s', $input->getType(), $input->getName());

                        if ($argString === "") {
                            return $argumentDeclaration;
                        }

                        return "$argString, $argumentDeclaration";
                    },
                    ""
                );

            $output = $node->getOutput()->getType();

            $options = $configVisitor->getNodeSettings($fullName);

            foreach ($options as $alias => $option) {
                $body = $node->getBody($option);

                $code = <<<PHP
public static function execute($arguments): $output
{
    $body
}
PHP;
                $nodeFullName = sprintf('%s_%s', $fullName, $alias);

                $classCreator->createClass($nodeFullName, $code);
            }
        }
    }
}
