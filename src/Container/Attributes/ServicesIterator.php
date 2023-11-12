<?php

namespace Awwar\MasterpiecePhp\Container\Attributes;

use Attribute;

#[Attribute]
class ServicesIterator
{
    public function __construct(private string $instanceOf)
    {
    }

    public function getInstanceOf(): string
    {
        return $this->instanceOf;
    }
}
