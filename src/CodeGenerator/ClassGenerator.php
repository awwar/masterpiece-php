<?php

namespace Awwar\MasterpiecePhp\CodeGenerator;

use Awwar\MasterpiecePhp\CodeGenerator\Utils\CommentStringify;
use Awwar\MasterpiecePhp\CodeGenerator\Utils\UsingStringify;

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
        $methods = "\r";

        foreach ($this->methods as $method) {
            $methods .= PHP_EOL . $method->generate();
        }

        // ToDo: code beautify option
        $methods = implode(PHP_EOL . "\t", explode(PHP_EOL, $methods));

        $using = UsingStringify::stringify($this->using);

        $comments = CommentStringify::stringify($this->comments);

        $classname = $this->name;
        $namespace = $this->namespace;

        return <<<PHP
<?php

namespace $namespace;
$using
$comments
class $classname
{{$methods}
}

PHP;
    }
}
