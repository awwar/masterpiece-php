<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ClassVisitorInterface
{
    public function createClass(string $namespace, string $name, string $code): void;
}
