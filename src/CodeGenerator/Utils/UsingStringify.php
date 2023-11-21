<?php

namespace Awwar\MasterpiecePhp\CodeGenerator\Utils;

class UsingStringify
{
    public static function stringify(array $using): string
    {
        $usingString = "\r";

        foreach ($using as $namespace => $aliases) {
            foreach ($aliases as $alias) {
                $usingString .= 'use ' . $namespace;

                if ($alias != null) {
                    $usingString .= 'as ' . $alias;
                }

                $usingString .= ';' . PHP_EOL;
            }
        }

        return $usingString;
    }
}
