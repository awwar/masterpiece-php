<?php

namespace Awwar\MasterpiecePhp\AddOns\App;

use Awwar\MasterpiecePhp\AddOn\AddOnCompileVisitorInterface;
use Awwar\MasterpiecePhp\AddOn\AddOnInterface;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\AppAddonVisitor;

class AppAddon implements AddOnInterface
{
    public function __construct(private AppAddonVisitor $addonVisitor)
    {
    }

    public function getName(): string
    {
        return 'app';
    }

    public function compile(AddOnCompileVisitorInterface $addOnCompileVisitor): void
    {
        foreach ($this->addonVisitor->getNodes() as $node) {
            $addOnCompileVisitor->setNode($node);
        }
    }
}
