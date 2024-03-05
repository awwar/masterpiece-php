<?php

namespace Awwar\MasterpiecePhp\CodeGenerator\Expression;

class Tokenizer
{
    private const CHAR_TYPE_MAP = [
        '('  => self::TYPE_SOLO,
        ')'  => self::TYPE_SOLO,
        '+'  => self::TYPE_SOLO,
        '-'  => self::TYPE_SOLO,
        '"'  => self::TYPE_SOLO,
        '\'' => self::TYPE_SOLO,
        ' '  => self::TYPE_SOLO,
        '|'  => self::TYPE_COMBO,
        '&'  => self::TYPE_COMBO,
        '>'  => self::TYPE_COMBO,
        '<'  => self::TYPE_COMBO,
        '='  => self::TYPE_COMBO,
    ];

    private const TYPE_PLAIN = 3;

    private const TYPE_SOLO = 2;

    private const TYPE_COMBO = 1;

    private const TYPE_UNKNOWN = 0;

    private int $pointer = 0;

    public function __construct(private string $rawString)
    {
    }

    public function next(): ?string
    {
        $token = null;
        $sequenceStartCharType = self::TYPE_UNKNOWN;

        do {
            $char = mb_substr($this->rawString, $this->pointer++, 1);

            if ('' === $char) {
                return $token;
            }

            $currentCharType = self::CHAR_TYPE_MAP[$char] ?? self::TYPE_PLAIN;

            if ($sequenceStartCharType === self::TYPE_UNKNOWN) {
                $sequenceStartCharType = $currentCharType;
            }

            if ($currentCharType !== $sequenceStartCharType) {
                $this->pointer--;

                return $token;
            }

            if ($currentCharType === self::TYPE_SOLO) {
                return $char;
            }

            $token .= $char;
        } while (true);
    }
}
