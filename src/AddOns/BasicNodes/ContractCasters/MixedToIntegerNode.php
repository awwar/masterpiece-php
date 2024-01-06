<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes\ContractCasters;

use Awwar\MasterpiecePhp\Contracts\basic_node_integer;

class MixedToIntegerNode
{
    public static function execute(mixed $mixed): basic_node_integer
    {
        if (false === is_numeric($mixed)) {
            throw new \RuntimeException('Unable to cast not numeric to integer');
        }

        return new basic_node_integer(value: (int) $mixed);
    }
}
