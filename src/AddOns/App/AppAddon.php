<?php

namespace Awwar\MasterpiecePhp\AddOns\App;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;

class AppAddon implements AddOnInterface
{
    public function __construct(private AddOnCompileVisitorInterface $addonVisitor)
    {
    }

    public function getName(): string
    {
        return 'app';
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        foreach ($this->addonVisitor->getNodesPatterns() as $node) {
            $addOnCompileVisitor->setNodePattern($node);
        }
    }
}
