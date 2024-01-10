<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Contract\Contract;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointPattern;
use Awwar\MasterpiecePhp\AddOn\Node\NodePattern;

interface AddOnCompileVisitorInterface
{
    public function setContract(Contract $contract): void;

    public function setNodePattern(NodePattern $nodePattern): void;

    public function setEndpointPattern(EndpointPattern $endpointPattern): void;
}
