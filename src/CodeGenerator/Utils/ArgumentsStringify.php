<?php

namespace Awwar\MasterpiecePhp\CodeGenerator\Utils;

class ArgumentsStringify
{
    public static function stringify(array $arguments): string
    {
        $argumentsString = '';

        foreach ($arguments as $argumentName => $argumentType) {
            $argumentDeclaration = "$argumentType \$$argumentName";

            if ($argumentsString === '') {
                $argumentsString = $argumentDeclaration;
            } else {
                $argumentsString = "$argumentsString, $argumentDeclaration";
            }
        }

        return $argumentsString;
    }
}
