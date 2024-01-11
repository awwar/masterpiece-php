<?php

namespace Awwar\MasterpiecePhp\AddOns\BasicNodes\ContractCasters;

use Awwar\MasterpiecePhp\App\base_integer_contract;
use RuntimeException;

class MixedToIntegerNode
{
    public static function execute(mixed $mixed): base_integer_contract
    {
        if (false === is_numeric($mixed)) {
            throw new RuntimeException('Unable to cast not numeric to integer');
        }

        return new base_integer_contract(value: (int) $mixed);
    }
}
