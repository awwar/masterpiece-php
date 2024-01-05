<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

use Awwar\MasterpiecePhp\CodeGenerator\Utils\ArgumentsStringify;
use Awwar\MasterpiecePhp\CodeGenerator\Utils\CommentStringify;

class MethodGenerator implements MethodGeneratorInterface, MethodBodyGeneratorInterface
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

    public function getBodyGenerator(): MethodBodyGeneratorInterface
    {
        return $this;
    }

    public function return(): MethodBodyGeneratorInterface
    {
        $this->body .= 'return ';

        return $this;
    }

    public function statement(string $statement): MethodBodyGeneratorInterface
    {
        if (false === str_ends_with($statement, ';')) {
            $statement .= ';';
        }

        $this->body .= $statement;

        return $this;
    }

    public function twoStatementsCartage(string $firstStatement, string $secondStatement): MethodBodyGeneratorInterface
    {
        $this->body .= sprintf('[%s, %s];', $firstStatement, $secondStatement);

        return $this;
    }

    public function newLine(): MethodBodyGeneratorInterface
    {
        $this->body .= PHP_EOL;
        
        return $this;
    }

    public function newLineAndTab(): MethodBodyGeneratorInterface
    {
        $this->newLine();
        $this->body .= "\t";

        return $this;
    }

    public function variable(string $name): MethodBodyGeneratorInterface
    {
        $this->body .= "$$name";

        return $this;
    }

    public function assign(): MethodBodyGeneratorInterface
    {
        $this->body .= " = ";

        return $this;
    }

    public function staticCall(string $from, string $method, array $args): MethodBodyGeneratorInterface
    {
        $argsStr = join(', ', $args);

        $this->body .= "$from::$method($argsStr)";

        return $this;
    }

    public function semicolon(): MethodBodyGeneratorInterface
    {
        $this->body .= ";";

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
