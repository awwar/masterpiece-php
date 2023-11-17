<?php

namespace Awwar\MasterpiecePhp\Compiler;

interface ClassVisitorInterface
{
    public function createClass(string $name, string $code): void;
}
