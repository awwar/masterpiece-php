<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ClassVisitorInterface
{
    public function createClass(string $className, string $body): void;
}
