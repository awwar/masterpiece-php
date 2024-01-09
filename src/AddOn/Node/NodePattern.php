<?php

namespace Awwar\MasterpiecePhp\AddOn\Node;

use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\BodyCompileContext;
use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\FragmentCompileContext;
use Awwar\MasterpiecePhp\Compiler\Util\NodeName;
use Closure;

class NodePattern
{
    public function __construct(
        private string $addonName,
        private string $name,
        private NodeInputSet $input,
        private NodeOutput $output,
        private ?Closure $nodeBodyCompileCallback = null,
        private ?Closure $nodeFragmentCompileCallback = null,
        private array $options = []
    ) {
        $this->nodeBodyCompileCallback ??= fn (BodyCompileContext $context) => $context->skip();

        $this->nodeFragmentCompileCallback ??= function (FragmentCompileContext $context) use ($name) {
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

    public function compileNodeBody(BodyCompileContext $context): void
    {
        call_user_func($this->nodeBodyCompileCallback, $context);
    }

    public function compileNodeFragment(FragmentCompileContext $context): void
    {
        call_user_func($this->nodeFragmentCompileCallback, $context);
    }

    public function getFullName(): string
    {
        return new NodeName($this->addonName, $this->name);
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
