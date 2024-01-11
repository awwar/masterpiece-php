<?php

namespace Awwar\MasterpiecePhp\Config;

class ExecuteMethodName
{
    public function __construct(private string $nodeName, private string $nodeAlias)
    {
    }

    public function __toString(): string
    {
        return 'execute_' . md5(sprintf('%s_%s', $this->nodeName, $this->nodeAlias));
    }
}
