<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

class FunctionCallArgumentsGenerator
{
    public function __construct(private MethodBodyGeneratorInterface $methodBodyGenerator)
    {
    }

    public function addArgumentAsVariable(string $string): FunctionCallArgumentsGenerator
    {
        $this->methodBodyGenerator->variable($string)->raw(', ');

        return $this;
    }

    public function end(): MethodBodyGeneratorInterface
    {
        return $this->methodBodyGenerator->rightTrim(', ')->raw(')');
    }
}
