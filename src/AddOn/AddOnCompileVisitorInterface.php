<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;
use Awwar\MasterpiecePhp\AddOn\Structure\AddOnStructure;

interface AddOnCompileVisitorInterface
{
    public function setNode(AddOnNode $node): void;

    public function setStructure(AddOnStructure $structure): void;
}
