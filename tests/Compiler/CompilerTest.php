<?php

namespace Compiler;

use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Compiler\CompileSetting;
use Awwar\MasterpiecePhp\Container\Container;
use Awwar\MasterpiecePhp\Container\ContainerFactory;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    private Container $container;

    private const TEST_COMPILE_PATH = __DIR__.'/../../var/test_compile';

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = (new ContainerFactory())->create();

        $filesystem = $this->container->get(Filesystem::class);

        $filesystem->recursiveRemoveDirectory(self::TEST_COMPILE_PATH);
    }

    public function testCompileWhenOk(): void
    {
        $compiler = $this->container->get(Compiler::class);

        $path = self::TEST_COMPILE_PATH.'/'.uniqid();

        $settings = new CompileSetting($path);

        $compiler->compile($settings);

        self::assertDirectoryExists($path);
    }
}
