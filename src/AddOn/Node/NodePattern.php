<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\FlowFragmentCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\NodeBodyCompileContext;
use Awwar\MasterpiecePhp\Config\NodeFullName;
use Closure;

class NodePattern
{
    public function __construct(
        private string $addonName,
        private string $name,
        private NodeInputSet $input,
        private NodeOutput $output,
        private ?Closure $nodeBodyCompileCallback = null,
        private ?Closure $flowFragmentCompileCallback = null,
        private array $options = []
    ) {
        $this->nodeBodyCompileCallback ??= fn (NodeBodyCompileContext $context) => $context->skip();

        $this->flowFragmentCompileCallback ??= function (FlowFragmentCompileContext $context) use ($name) {
            $methodCall = $context->getMethodBodyGenerator()
                ->variable($context->getSocketName())
                ->assign()
                ->constant($context->getNodeName())->staticAccess()->functionCall($context->getMethodName());

            foreach ($context->getArgs() as $arg) {
                $methodCall->addArgumentAsVariable($arg);
            }

            $methodCall->end()->semicolon();
        };
    }

    public function getInput(): NodeInputSet
    {
        return $this->input;
    }

    public function getOutput(): NodeOutput
    {
        return $this->output;
    }

    public function compileNodeBody(NodeBodyCompileContext $context): void
    {
        call_user_func($this->nodeBodyCompileCallback, $context);
    }

    public function compileFlowFragment(FlowFragmentCompileContext $context): void
    {
        call_user_func($this->flowFragmentCompileCallback, $context);
    }

    public function getFullName(): string
    {
        return new NodeFullName($this->addonName, $this->name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddonName(): string
    {
        return $this->addonName;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isOptionsRequired(): bool
    {
        return false === empty($this->options);
    }
}
