<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

use Awwar\MasterpiecePhp\CodeGenerator\Utils\ArgumentsStringify;
use Awwar\MasterpiecePhp\CodeGenerator\Utils\CommentStringify;

class MethodGenerator implements MethodGeneratorInterface
{
    private array $arguments = [];
    private array $comments = [];
    private string $returnType = 'mixed';
    private string $body = '';

    public function __construct(private string $name, private ClassGeneratorInterface $classGenerator)
    {
    }

    public function addComment(string $comment): MethodGeneratorInterface
    {
        $this->comments[] = $comment;

        return $this;
    }

    public function addArgument(string $name, string $type): MethodGeneratorInterface
    {
        $this->arguments[$name] = $type;

        return $this;
    }

    public function setReturnType(string $type): MethodGeneratorInterface
    {
        $this->returnType = $type;

        return $this;
    }

    public function setBody(string $body): MethodGeneratorInterface
    {
        $this->body = $body;

        return $this;
    }

    public function getClass(): ClassGeneratorInterface
    {
        return $this->classGenerator;
    }

    public function generate(): string
    {
        $arguments = ArgumentsStringify::stringify($this->arguments);
        $comments = CommentStringify::stringify($this->comments);
        $body = trim($this->body);
        $returnType = $this->returnType;
        $name = $this->name;

        return <<<PHP
$comments
public static function $name($arguments): $returnType
{
    $body
}
PHP;
    }
}
