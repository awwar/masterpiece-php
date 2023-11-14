<?php

namespace Awwar\MasterpiecePhp\AddOn;

interface AddOnInterface
{
    public function getName(): string;

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void;
}
