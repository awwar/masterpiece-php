<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler;

use Awwar\MasterpiecePhp\Compiler\CompileContext;
use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Awwar\MasterpiecePhp\Tests\CaseWithContainer;

class CompilerTest extends CaseWithContainer
{
    private const TEST_COMPILE_PATH = __DIR__ . '/../../var/test_compile';

    protected function setUp(): void
    {
        parent::setUp();

        $filesystem = $this->container->get(Filesystem::class);

        $filesystem->recursiveRemoveDirectory(self::TEST_COMPILE_PATH);
    }

    public function testCompileWhenOk(): void
    {
        $compiler = $this->container->get(Compiler::class);

        $path = self::TEST_COMPILE_PATH . '/' . uniqid();

        $settings = new CompileContext($path);

        $compiler->compile($settings);

        self::assertDirectoryExists($path);
    }
}

