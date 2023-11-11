<?php

namespace Awwar\MasterpiecePhp\Container;

use Awwar\MasterpiecePhp\Filesystem\Filesystem;
use ReflectionClass;
use SplFileInfo;

class ServicesDiscover
{
    public function discover(string $scanPath): iterable
    {
        $filesystem = new Filesystem();

        return $filesystem->iterateOverFiles(
            $scanPath,
            function (SplFileInfo $file) use ($scanPath) {
                if ($file->isFile() === false) {
                    return null;
                }

                if ($file->getExtension() !== 'php') {
                    return null;
                }

                if (str_starts_with($file->getRealPath(), __DIR__)) {
                    return null;
                }

                $replacements = [
                    $scanPath => 'Awwar\MasterpiecePhp',
                    '/'       => '\\',
                    '.php'    => '',
                ];

                $fqcn = strtr($file->getPathname(), $replacements);

                if (!class_exists($fqcn) && !interface_exists($fqcn)) {
                    return null;
                }

                return new ReflectionClass($fqcn);
            }
        );
    }
}
