<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use RuntimeException;
use Traversable;

class NodeInputSet implements \IteratorAggregate
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

    public function getIterator(): Traversable
    {
        foreach ($this->list as $name => $input) {
            yield $name => $input;
        }
    }
}
