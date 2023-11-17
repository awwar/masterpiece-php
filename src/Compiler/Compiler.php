<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompiler;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\AppAddon;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompiler;
use Awwar\MasterpiecePhp\Compiler\ConfigCompiler\ConfigCompileStrategyFactory;
use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Throwable;

#[ForDependencyInjection]
class Compiler
{
    public function __construct(
        private Filesystem $filesystem,
        private AddOnCompiler $addOnCompiler,
        private ConfigCompileStrategyFactory $factory
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

        $configVisitor = new ConfigVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $strategy = $this->factory->create($config->getType());

            $strategy->prefetch($config->getParams(), $configVisitor);
        }

        // ToDo: Need to fold this piece of code in separate class
        // May be as AppAddon
        $classVisitor = new ClassVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $strategy = $this->factory->create($config->getType());

            // ToDo: it`s not work now
            //  if ($strategy->isDemand($config->getName(), $configVisitor) === false) {
            //      continue;
            // }

            $strategy->compile($config->getParams(), $classVisitor);
        }
        //ToDo: end

        foreach ($compileContext->getAddOns() as $addOn) {
            $this->addOnCompiler->compile($addOn, $configVisitor, $classVisitor);
        }

        foreach ($classVisitor->getClasses() as $classname => $executable) {
            $filepath = sprintf('%s/%s.php', rtrim($dirname, '\/'), $classname);

            //ToDo: implement code generator like:
            // $codeGen->createClass('className')->withFunction('foo', 'a')->willReturn('int')->end()->end()
            // well maybe a little prettier
            $class = <<<PHP
<?php
namespace Awwar\MasterpiecePhp\Nodes;
class $classname
{
$executable
}
PHP;

            $this->filesystem->createFile($filepath, $class);
        }
    }
}
