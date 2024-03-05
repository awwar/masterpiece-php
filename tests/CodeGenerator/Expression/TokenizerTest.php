<?php

namespace Awwar\MasterpiecePhp\Tests\CodeGenerator\Expression;

use Awwar\MasterpiecePhp\CodeGenerator\Expression\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    public function testTokenizer(): void
    {
        $tokenizer = new Tokenizer("($0 + 1) || ($1 ===f'hello world'");

        $parsedData = [];
        $expectedData = [
            '(',
            '$0',
            ' ',
            '+',
            ' ',
            '1',
            ')',
            ' ',
            '||',
            ' ',
            '(',
            '$1',
            ' ',
            '===',
            'f',
            '\'',
            'hello',
            ' ',
            'world',
            '\'',
            null,
        ];

        do {
            $token = $tokenizer->next();

            $parsedData[] = $token;
        } while ($token !== null);

        self::assertSame($expectedData, $parsedData);
    }
}
