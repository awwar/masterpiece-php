<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

class ClassGenerator implements ClassGeneratorInterface
{
    private string $namespace = "";
    private array $comments = [];
    private array $using = [];
    /** @var MethodGenerator[] $methods */
    private array $methods = [];

    public function __construct(private string $name)
    {
    }

    public function addComment(string $comment): ClassGeneratorInterface
    {
        $this->comments[] = $comment;

        return $this;
    }

    public function addMethod(string $name): MethodGeneratorInterface
    {
        $method = new MethodGenerator(name: $name, classGenerator: $this);

        $this->methods[$name] = $method;

        return $method;
    }

    public function setNamespace(string $name): ClassGeneratorInterface
    {
        $this->namespace = $name;

        return $this;
    }

    public function addUsing(string $name, ?string $alias = null): ClassGeneratorInterface
    {
        $this->using[$name][] = $alias;

        return $this;
    }

    public function generate(): string
    {
        $methods = "";

        foreach ($this->methods as $method) {
            $methods .= PHP_EOL . $method->generate() ;
        }

        $usings = "";

        foreach ($this->using as $namespace => $aliases) {
            foreach ($aliases as $alias) {
                $usings .= 'use ' . $namespace;

                if ($alias != null) {
                    $usings .= 'as ' . $alias;
                }

                $usings .= ';' . PHP_EOL;
            }
        }

        $comments = empty($this->comments) ? '' : '//' . join(PHP_EOL . '//', $this->comments);

        $classname = $this->name;
        $namespace = $this->namespace;

        return <<<PHP
<?php
namespace $namespace;
$usings
$comments
class $classname
{{$methods}
}
PHP;
    }
}
