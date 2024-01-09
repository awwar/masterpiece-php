<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;

interface NodePatternObtainerInterface
{
    public function getNodePattern(string $addonName, string $nodeName): NodePattern;
}
