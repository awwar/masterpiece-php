<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

class MethodGenerator implements MethodGeneratorInterface
{
    private array $arguments = [];
    private string $returnType = 'mixed';
    private string $body = '';

    public function __construct(private string $name, private ClassGeneratorInterface $classGenerator)
    {
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
        $arguments = '';

        foreach ($this->arguments as $argumentName => $argumentType) {
            $argumentDeclaration = "$argumentType \$$argumentName";

            if ($arguments === '') {
                $arguments = $argumentDeclaration;
            } else {
                $arguments = "$arguments, $argumentDeclaration";
            }
        }

        $body = $this->body;
        $returnType = $this->returnType;
        $name = $this->name;

        return <<<PHP
public static function $name($arguments): $returnType
{
    $body
}
PHP;
    }
}
