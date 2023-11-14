<?php

namespace Awwar\MasterpiecePhp\Filesystem;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Closure;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[ForDependencyInjection]
class Filesystem
{
    public function iterateOverFiles(string $path, Closure $callback): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            $result = call_user_func($callback, $file);

            if ($result !== null) {
                yield $result;
            }
        }
    }

    public function createDirectory(string $path): void
    {
        mkdir($path, permissions: 0755, recursive: true);
    }

    public function createFile(string $path, string $content): void
    {
        $handle = fopen($path, "w");

        try {
            fwrite($handle, $content);
        } finally {
            fclose($handle);
        }
    }

    public function recursiveRemoveDirectory(string $path): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /*
         * @var SplFileObject $file
         */
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }
}
