<?php

namespace Container;

use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Container\ContainerFactory;
use PHPUnit\Framework\TestCase;

class ContainerFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new ContainerFactory();

        $container = $factory->create();

        $service = $container->get(Compiler::class);

        self::assertIsObject($service);
        self::assertInstanceOf(Compiler::class, $service);
    }
}
