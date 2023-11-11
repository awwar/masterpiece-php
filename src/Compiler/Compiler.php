<?php

namespace Awwar\MasterpiecePhp\Compiler;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use Throwable;

#[ForDependencyInjection]
class Compiler
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function compile(CompileSetting $settings): void
    {
        try {
            $this->filesystem->recursiveRemoveDirectory($settings->getGenerationPath());
        } catch (Throwable) {

        }

        $this->filesystem->createDirectory($settings->getGenerationPath());
    }
}
