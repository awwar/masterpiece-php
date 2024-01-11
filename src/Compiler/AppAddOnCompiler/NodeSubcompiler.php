<?php

namespace Awwar\MasterpiecePhp\Compiler\AppAddOnCompiler;

use Awwar\MasterpiecePhp\AddOn\Node\NodeCompileContext\NodeFragmentCompileContext;
use Awwar\MasterpiecePhp\AddOn\NodeTemplateObtainerInterface;
use Awwar\MasterpiecePhp\AddOn\SubcompileInterface;
use Awwar\MasterpiecePhp\CodeGenerator\MethodBodyGeneratorInterface;
use Awwar\MasterpiecePhp\Config\ExecuteMethodName;
use Awwar\MasterpiecePhp\Config\NodeFullName;
use RuntimeException;

class NodeSubcompiler implements SubcompileInterface
{
    private array $visitedConditions = [];

    public function __construct(
        private array $params,
        private string $nodeName,
        private NodeTemplateObtainerInterface $nodeTemplateObtainer,
    ) {
    }

    public function subcompileSocketCondition(
        MethodBodyGeneratorInterface $methodBodyGenerator,
        string $nextSocketName,
        int $condition
    ): void {
        $key = sprintf('%s--%d', $nextSocketName, $condition);

        if ($this->visitedConditions[$key] ?? false) {
            return;
        }
        $this->visitedConditions[$key] = true;

        $socketName = $this->params['sockets'][$nextSocketName]['transition'][$condition]['socket'] ?? throw new RuntimeException(
                "Condition $condition for socket $nextSocketName not found!"
            );

        $socket = $this->params['sockets'][$socketName];
        $nodeAlias = $socket['node_alias'];

        $args = [];

        foreach ($socket['input'] ?? [] as $inputSettings) {
            $args[] = $inputSettings['variable'] ?? $inputSettings['node_alias'];
        }

        /** @var NodeFullName $nodeFullName */
        $nodeFullName = $this->params['nodes'][$nodeAlias]['node'];
        $options = $this->params['nodes'][$nodeAlias]['option'] ?? [];

        $node = $this->nodeTemplateObtainer->getNodeTemplate((string) $nodeFullName);

        $methodName = new ExecuteMethodName(nodeName: $this->nodeName, nodeAlias: $socket['node_alias']);

        $fragmentCompileContext = new NodeFragmentCompileContext(
            methodBodyGenerator: $methodBodyGenerator,
            subcompile: $this,
            socketName: $socketName,
            args: $args,
            nodeName: $node->getFullName(),
            functionName: $methodName,
            options: $options
        );

        $node->compileNodeFragment($fragmentCompileContext);

        $nextTransitions = $socket['transition'] ?? [];

        if (count($nextTransitions) > 0) {
            $methodBodyGenerator->newLineAndTab();
        }

        foreach ($socket['transition'] ?? [] as $i => $transition) {
            $this->subcompileSocketCondition($methodBodyGenerator, $socketName, $i);
        }
    }
}
