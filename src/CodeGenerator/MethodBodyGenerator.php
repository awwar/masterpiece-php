<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

class MethodBodyGenerator implements MethodBodyGeneratorInterface
{
    private string $body = '';

    public function __construct(private MethodGeneratorInterface $methodGenerator)
    {
    }

    public function code(string $statement): MethodBodyGeneratorInterface
    {
        $this->body .= $statement;

        return $this;
    }

    public function rightTrim(string $substring): MethodBodyGeneratorInterface
    {
        $this->body = rtrim($this->body, $substring);

        return $this;
    }

    public function return(): MethodBodyGeneratorInterface
    {
        return $this->code('return ');
    }

    public function statement(string $statement): MethodBodyGeneratorInterface
    {
        if (false === str_ends_with($statement, ';')) {
            $statement .= ';';
        }

        return $this->code($statement);
    }

    public function twoStatementsCartage(string $firstStatement, string $secondStatement): MethodBodyGeneratorInterface
    {
        return $this->statement(sprintf('[%s, %s]', $firstStatement, $secondStatement));
    }

    public function newLine(): MethodBodyGeneratorInterface
    {
        return $this->code(PHP_EOL);
    }

    public function newLineAndTab(): MethodBodyGeneratorInterface
    {
        $this->newLine();

        return $this->code("\t");
    }

    public function variable(string $name): MethodBodyGeneratorInterface
    {
        return $this->code("$$name");
    }

    public function constant(string $name): MethodBodyGeneratorInterface
    {
        return $this->code($name);
    }

    public function assign(): MethodBodyGeneratorInterface
    {
        return $this->code(" = ");
    }

    public function objectAccess(): MethodBodyGeneratorInterface
    {
        return $this->code("->");
    }

    public function staticAccess(): MethodBodyGeneratorInterface
    {
        return $this->code("::");
    }

    public function functionCall(string $method): FunctionCallArgumentsGenerator
    {
        $this->code("$method(");

        return new FunctionCallArgumentsGenerator($this);
    }

    public function semicolon(): MethodBodyGeneratorInterface
    {
        return $this->code(";");
    }

    public function generate(): string
    {
        return trim($this->body);
    }

    public function end(): MethodGeneratorInterface
    {
        return $this->methodGenerator;
    }
}
