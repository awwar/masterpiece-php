<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompiler;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompiler;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Throwable;

#[ForDependencyInjection]
class Compiler
{
    public function __construct(
        private Filesystem $filesystem,
        private AddOnCompiler $addOnCompiler,
        private ConfigCompiler $configCompiler
    ) {
    }

    public function compile(CompileContext $compileContext): void
    {
        $path = $compileContext->getGenerationPath();

        try {
            $this->filesystem->recursiveRemoveDirectory($path);
        } catch (Throwable) {

        }

        $this->filesystem->createDirectory($path);

        $dirname = realpath($path);

        $classVisitor = new ClassVisitor();

        foreach ($compileContext->getAddOns() as $addOn) {
            $this->addOnCompiler->compile($addOn, $classVisitor);
        }

        foreach ($compileContext->getConfigs() as $config) {
            $this->configCompiler->compile($config, $classVisitor);
        }

        foreach ($classVisitor->getClasses() as $namespace => $classes) {
            foreach ($classes as $classname => $executable) {
                $fullName = sprintf('%s_%s', $namespace, $classname);
                $filepath = sprintf('%s/%s.php', rtrim($dirname, '\/'), $fullName);

                $class = <<<PHP
<?php
namespace Awwar\MasterpiecePhp\Nodes;
class $fullName
{
$executable
}
PHP;

                $this->filesystem->createFile($filepath, $class);
            }
        }
    }
}
