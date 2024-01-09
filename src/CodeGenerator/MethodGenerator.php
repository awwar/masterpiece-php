<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

use Awwar\MasterpiecePhp\CodeGenerator\Utils\ArgumentsStringify;
use Awwar\MasterpiecePhp\CodeGenerator\Utils\CommentStringify;

class MethodGenerator implements MethodGeneratorInterface
{
    private array $parameters = [];
    private array $comments = [];
    private ?string $returnType = null;
    private MethodBodyGenerator $bodyGenerator;
    private bool $isStatic = false;

    public function __construct(private string $name, private ClassGeneratorInterface $classGenerator)
    {
        $this->bodyGenerator = new MethodBodyGenerator($this);
    }

    public function addComment(string $comment): MethodGeneratorInterface
    {
        $this->comments[] = $comment;

        return $this;
    }

    public function addParameter(string $name, string $type): MethodGeneratorInterface
    {
        $this->parameters[$name] = $type;

        return $this;
    }

    public function setReturnType(?string $type): MethodGeneratorInterface
    {
        $this->returnType = $type;

        return $this;
    }

    public function makeStatic(): MethodGeneratorInterface
    {
        $this->isStatic = true;

        return $this;
    }

    public function getBodyGenerator(): MethodBodyGeneratorInterface
    {
        return $this->bodyGenerator;
    }

    public function end(): ClassGeneratorInterface
    {
        return $this->classGenerator;
    }

    public function generate(): string
    {
        $arguments = ArgumentsStringify::stringify($this->parameters);
        $comments = CommentStringify::stringify($this->comments);
        $body = $this->bodyGenerator->generate();
        $returnDeclaration = $this->returnType === null ? '' : sprintf(': %s', $this->returnType);
        $name = $this->name;
        $staticKeyword = $this->isStatic ? ' static' : '';

        return <<<PHP
$comments
public$staticKeyword function $name($arguments)$returnDeclaration
{
    $body
}
PHP;
    }
}
