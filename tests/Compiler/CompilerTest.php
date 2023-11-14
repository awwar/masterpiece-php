<?php

namespace Awwar\MasterpiecePhp\Tests\Compiler;

use Awwar\MasterpiecePhp\AddOns\BasicNodes\BasicNodeAddon;
use Awwar\MasterpiecePhp\Compiler\CompileContext;
use Awwar\MasterpiecePhp\Compiler\Compiler;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Awwar\MasterpiecePhp\Tests\CaseWithContainer;
use Throwable;

class CompilerTest extends CaseWithContainer
{
    private const TEST_COMPILE_PATH = __DIR__ . '/../../var/test_compile';

    protected function setUp(): void
    {
        parent::setUp();

        $filesystem = $this->container->get(Filesystem::class);

        try {
            $filesystem->recursiveRemoveDirectory(self::TEST_COMPILE_PATH);
        } catch (Throwable) {

        }
    }

    public function testCompileWhenOk(): void
    {
        $compiler = $this->container->get(Compiler::class);

        $path = self::TEST_COMPILE_PATH . '/' . uniqid();

        $settings = new CompileContext($path);

        $settings->addAddOn(new BasicNodeAddon());

        $compiler->compile($settings);

        self::assertDirectoryExists($path);

        include_once $path . '/basic_node_sum.php';

        self::assertTrue(class_exists(\Awwar\MasterpiecePhp\Nodes\basic_node_sum::class));
    }
}

