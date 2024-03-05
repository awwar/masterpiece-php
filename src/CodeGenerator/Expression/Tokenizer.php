<?php

namespace Awwar\MasterpiecePhp\CodeGenerator\Expression;

class Tokenizer
{
    private const SPECIAL_CHARS = [...self::SOLO_CHARS, '>', '<', '='];

    private const SOLO_CHARS = ['(', ')', '+', '-', '"', '\'', ' '];

    private const TYPE_PLAIN = 1;

    private const TYPE_SPECIAL = 0;

    private const TYPE_UNKNOWN = -1;

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

            $currentCharType = in_array($char, self::SPECIAL_CHARS) ? self::TYPE_PLAIN : self::TYPE_SPECIAL;

            if ($sequenceStartCharType === self::TYPE_UNKNOWN) {
                $sequenceStartCharType = $currentCharType;
            }

            if ($currentCharType !== $sequenceStartCharType) {
                $this->pointer--;
                return $token;
            }

            if (in_array($char, self::SOLO_CHARS)) {
                if (null !== $token) {
                    $this->pointer--;
                    return $token;
                }

                return $char;
            }

            $token .= $char;
        } while (true);
    }
}
