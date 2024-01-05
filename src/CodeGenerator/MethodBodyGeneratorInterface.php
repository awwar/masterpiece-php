<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

interface MethodBodyGeneratorInterface
{
    public function return(): MethodBodyGeneratorInterface;

    public function statement(string $statement): MethodBodyGeneratorInterface;

    public function variable(string $name): MethodBodyGeneratorInterface;

    public function assign(): MethodBodyGeneratorInterface;

    public function staticCall(string $from, string $method, array $args): MethodBodyGeneratorInterface;

    public function semicolon(): MethodBodyGeneratorInterface;

    public function newLine(): MethodBodyGeneratorInterface;

    public function newLineAndTab(): MethodBodyGeneratorInterface;

    public function twoStatementsCartage(string $firstStatement, string $secondStatement): MethodBodyGeneratorInterface;
}
