<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use RuntimeException;

class NodeInputSet
{
    private array $list = [];

    public static function create(): self
    {
        return new self();
    }

    public function push(NodeInput $input): self
    {
        $name = $input->getName();

        if (isset($this->list[$name])) {
            throw new RuntimeException(sprintf('Node input %s already declared', $name));
        }

        $this->list[$name] = $input;

        return $this;
    }

    public function reduce(callable $reducer, mixed $startValue = null): mixed
    {
        $result = $startValue;

        foreach ($this->list as $input) {
            $result = call_user_func($reducer, $input, $result);
        }

        return $result;
    }
}