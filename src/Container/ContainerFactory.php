<?php

namespace Awwar\MasterpiecePhp\Container;

use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public function create(): ContainerInterface
    {
        $scanPath = __DIR__.'/..';

        $discover = new ServicesDiscover();

        $classIterator = $discover->discover($scanPath);

        return new Container($classIterator);
    }
}
