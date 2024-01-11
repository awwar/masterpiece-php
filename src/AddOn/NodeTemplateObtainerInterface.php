<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Node\NodeTemplate;

interface NodeTemplateObtainerInterface
{
    public function getNodeTemplate(string $nodeFullName): NodeTemplate;
}
