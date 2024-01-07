<?php

namespace Awwar\MasterpiecePhp\Compiler\Util;

class ExecuteMethodName
{
    public function __construct(private string $flowName, private string $nodeAlias)
    {
    }

    public function __toString(): string
    {
        return 'execute_' . md5(sprintf('%s_%s', $this->flowName, $this->nodeAlias));
    }
}
