<?php

namespace Awwar\MasterpiecePhp\Compiler\AddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\CodeGenerator\ClassGenerator;
use Awwar\MasterpiecePhp\Compiler\ClassVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\ConfigVisitorInterface;
use Awwar\MasterpiecePhp\Compiler\Util\ContractName;
use Awwar\MasterpiecePhp\Compiler\Util\ExecuteMethodName;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;

#[ForDependencyInjection]
class AddOnCompiler
{
    public function compile(
        AddOnInterface $addOn,
        ConfigVisitorInterface $configVisitor,
        ClassVisitorInterface $classVisitor
    ): void {
    }
}
