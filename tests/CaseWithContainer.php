<?php

namespace Awwar\MasterpiecePhp\Tests;

use Awwar\MasterpiecePhp\Container\Container;
use Awwar\MasterpiecePhp\Container\ContainerFactory;
use PHPUnit\Framework\TestCase;

class CaseWithContainer extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = (new ContainerFactory())->create();
    }
}
