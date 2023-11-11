<?php

namespace Awwar\MasterpiecePhp\Container;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

class Container implements ContainerInterface
{
    private array $services = [];

    private array $interfaces = [];

    /**
     * @var iterable<class-string, ReflectionMethod> $classes
     */
    private array $classes = [];

    /**
     * @param iterable<ReflectionClass> $serviceClasses
     */
    public function __construct(iterable $serviceClasses)
    {
        foreach ($serviceClasses as $reflection) {
            if (count($reflection->getAttributes(ForDependencyInjection::class)) === 0) {
                continue;
            }

            $fqcn = $reflection->getName();

            if ($reflection->isInterface()) {
                $this->interfaces[$fqcn] = ($interfaces[$fqcn] ?? []);
            }

            if ($reflection->isInstantiable() === false) {
                continue;
            }

            foreach ($reflection->getInterfaceNames() as $interfaceName) {
                $this->interfaces[$interfaceName][] = $fqcn;
            }

            $this->classes[$fqcn] = new ClassSettings(
                fqcn: $fqcn,
                constructorParams: ($reflection->getConstructor()?->getParameters() ?? [])
            );
        }
    }


    /**
     * @psalm-template T of object
     * @psalm-param    class-string<T> $id
     * @psalm-return   T
     */
    public function get(string $id): object
    {
        if ($this->has($id)) {
            return $this->services[$id];
        }

        if (isset($this->interfaces[$id])) {
            $classes = $this->interfaces[$id];

            if (count($classes) > 1) {
                throw new RuntimeException("Too mach classes implements $id interface");
            }

            if (count($classes) === 0) {
                throw new RuntimeException("Nobody implements $id interface");
            }

            $id = $classes[0];
        }

        if (false === isset($this->classes[$id])) {
            throw new NotFoundException(sprintf('Service %s not found', $id));
        }

        /*
         * @var ClassSettings $classSettings
         */
        $classSettings = $this->classes[$id];

        $arguments = [];

        /*
         * @var ReflectionParameter $parameter
         */
        foreach ($classSettings->getConstructorParams() as $parameter) {
            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $arguments[] = $this->get($type->getName());
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                } else {
                    if ($parameter->allowsNull()) {
                        $arguments[] = null;
                    } else {
                        throw new RuntimeException(
                            sprintf('Cant instantiate service %s, cause of %s parameter', $id, $parameter->getName())
                        );
                    }
                }
            }
        }

        $className = $classSettings->getFqcn();

        return $this->services[$id] = new $className(...$arguments);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
