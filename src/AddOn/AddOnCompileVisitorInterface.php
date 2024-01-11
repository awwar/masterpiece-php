<?php

namespace Awwar\MasterpiecePhp\AddOn;

use Awwar\MasterpiecePhp\AddOn\Contract\ContractTemplate;
use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointTemplate;
use Awwar\MasterpiecePhp\AddOn\Node\NodeTemplate;

interface AddOnCompileVisitorInterface
{
    public function setContractTemplate(ContractTemplate $contractTemplate): void;

    public function setNodeTemplate(NodeTemplate $nodeTemplate): void;

    public function setEndpointTemplate(EndpointTemplate $endpointTemplate): void;
}
