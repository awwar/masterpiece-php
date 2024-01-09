<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;

interface SubcompileInterface
{
    public function subcompileSocketCondition(MethodBodyGeneratorInterface $methodBodyGenerator, string $nextSocketName, int $condition): void;
}
