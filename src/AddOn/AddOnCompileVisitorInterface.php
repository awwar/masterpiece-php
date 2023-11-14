<?php

namespace Awwar\MasterpiecePhp\AddOn;

interface AddOnCompileVisitorInterface
{
    public function createNode(string $name, NodeExecutable $executable): void;
}
