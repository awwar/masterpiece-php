<?php

namespace Awwar\MasterpiecePhp\AddOn\Endpoint;

use Awwar\MasterpiecePhp\AddOn\Endpoint\EndpointCompileContext\EndpointBodyCompileContext;
use Awwar\MasterpiecePhp\Config\EndpointName;
use Closure;

class EndpointTemplate
{
    public function __construct(
        private string $addonName,
        private string $name,
        private Closure $nodeBodyCompileCallback,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return new EndpointName($this->addonName, $this->name);
    }

    public function getAddonName(): string
    {
        return $this->addonName;
    }

    public function compileEndpointBody(EndpointBodyCompileContext $context): void
    {
        call_user_func($this->nodeBodyCompileCallback, $context);
    }
}
