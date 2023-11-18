<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\AddOns\App\AppAddon;
use Awwar\MasterpiecePhp\Compiler\AddOnCompiler\AddOnCompiler;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\AppAddonVisitor;
use Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler\ConfigCompileStrategyFactory;
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
            $this
                ->factory
                ->create($config->getType())
                ->prefetch($config->getParams(), $configVisitor);
        }

        // ToDo: Need to fold this piece of code in separate class
        // May be as AppAddon
        $appAddonVisitor = new AppAddonVisitor();

        foreach ($compileContext->getConfigs() as $config) {
            $this
                ->factory
                ->create($config->getType())
                ->compile($config->getParams(), $appAddonVisitor);
        }

        $compileContext->addAddOn(new AppAddon($appAddonVisitor));
        //ToDo: end

        $classVisitor = new ClassVisitor();

        foreach ($compileContext->getAddOns() as $addOn) {
            $this->addOnCompiler->compile($addOn, $configVisitor, $classVisitor);
        }

        foreach ($classVisitor->getClasses() as $className => $content) {
            $filepath = sprintf('%s/%s.php', rtrim($dirname, '\/'), $className);

            $this->filesystem->createFile($filepath, $content);
        }
    }
}
