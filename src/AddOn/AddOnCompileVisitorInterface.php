<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;

interface AddOnCompileVisitorInterface
{
    public function setNode(AddOnNode $node): void;
}
