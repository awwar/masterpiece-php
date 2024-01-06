<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Node\AddOnNode;

interface AddOnCompileVisitorInterface
{
    public function setContract(Contract $contract): void;

    public function setNode(AddOnNode $node): void;
}
