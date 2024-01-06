<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface MethodBodyGeneratorInterface
{
    public function code(string $statement): MethodBodyGeneratorInterface;

    public function return(): MethodBodyGeneratorInterface;

    public function statement(string $statement): MethodBodyGeneratorInterface;

    public function variable(string $name): MethodBodyGeneratorInterface;

    public function assign(): MethodBodyGeneratorInterface;

    public function constant(string $name): MethodBodyGeneratorInterface;

    public function objectAccess(): MethodBodyGeneratorInterface;

    public function staticAccess(): MethodBodyGeneratorInterface;

    public function functionCall(string $method): FunctionCallArgumentsGenerator;

    public function semicolon(): MethodBodyGeneratorInterface;

    public function newLine(): MethodBodyGeneratorInterface;

    public function newLineAndTab(): MethodBodyGeneratorInterface;

    public function twoStatementsCartage(string $firstStatement, string $secondStatement): MethodBodyGeneratorInterface;

    public function rightTrim(string $substring): MethodBodyGeneratorInterface;

    public function end(): MethodGeneratorInterface;
}
