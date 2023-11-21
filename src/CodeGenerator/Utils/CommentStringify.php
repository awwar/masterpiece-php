<?php

namespace Awwar\MasterpiecePhp\CodeGenerator\Utils;

class CommentStringify
{
    public static function stringify(array $comments): string
    {
        if (empty($comments)) {
            return "\r";
        }

        return sprintf("/**\n * %s\n */", join(PHP_EOL . ' * ', $comments));
    }
}
