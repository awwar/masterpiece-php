<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

class MethodBodyGenerator implements MethodBodyGeneratorInterface
{
    private string $body = '';

    public function __construct(private MethodGeneratorInterface $methodGenerator)
    {
    }

    public function rightTrim(string $substring): MethodBodyGeneratorInterface
    {
        $this->body = rtrim($this->body, $substring);

        return $this;
    }

    public function return(): MethodBodyGeneratorInterface
    {
        return $this->raw('return ');
    }

    public function raw(string $statement): MethodBodyGeneratorInterface
    {
        $this->body .= $statement;

        return $this;
    }

    public function comment(string $message): MethodBodyGeneratorInterface
    {
        return $this->raw('//' . $message);
    }

    public function newLineAndTab(): MethodBodyGeneratorInterface
    {
        $this->newLine();

        return $this->raw("\t");
    }

    public function newLine(): MethodBodyGeneratorInterface
    {
        return $this->raw(PHP_EOL);
    }

    public function variable(string $name): MethodBodyGeneratorInterface
    {
        return $this->raw("$$name");
    }

    public function constant(string $name): MethodBodyGeneratorInterface
    {
        return $this->raw($name);
    }

    public function assign(): MethodBodyGeneratorInterface
    {
        return $this->raw(" = ");
    }

    public function objectAccess(): MethodBodyGeneratorInterface
    {
        return $this->raw("->");
    }

    public function staticAccess(): MethodBodyGeneratorInterface
    {
        return $this->raw("::");
    }

    public function functionCall(string $method): FunctionCallArgumentsGenerator
    {
        $this->raw("$method(");

        return new FunctionCallArgumentsGenerator($this);
    }

    public function semicolon(): MethodBodyGeneratorInterface
    {
        return $this->raw(";");
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
