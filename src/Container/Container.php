<?php

namespace Awwar\MasterpiecePhp\Container;

use Awwar\MasterpiecePhp\Container\Attributes\ForDependencyInjection;
use Awwar\MasterpiecePhp\Container\Attributes\ServicesIterator;
use Awwar\MasterpiecePhp\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use ReflectionClass;
use RuntimeException;

class Container implements ContainerInterface
{
    private array $services = [];

    private array $interfaces = [];

    /**
     * @var iterable<class-string, ClassSettings> $classes
     */
    private array $classes = [];

    /**
     * @param iterable<ReflectionClass> $serviceClasses
     */
    public function __construct(iterable $serviceClasses)
    {
        foreach ($serviceClasses as $reflection) {
            if (empty($reflection->getAttributes(ForDependencyInjection::class))) {
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
        if (false === $this->has($id)) {
            $this->services[$id] = $this->createService($id);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    private function createService(string $serviceId): object
    {
        $id = $serviceId;

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
            throw new NotFoundException(sprintf('Class %s not found in container', $id));
        }

        $classSettings = $this->classes[$id];

        $arguments = [];

        foreach ($classSettings->getConstructorParams() as $parameter) {
            $type = $parameter->getType();

            if (false === empty($parameter->getAttributes(ServicesIterator::class))) {
                /** @var ReflectionAttribute $attribute */
                $attribute = $parameter->getAttributes(ServicesIterator::class)[0];

                $instanceOf = $attribute->newInstance()->getInstanceOf();

                $arguments[] = $this->getInstanceOfIterator($instanceOf);
            } elseif ($type && !$type->isBuiltin()) {
                $arguments[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
            } elseif ($parameter->allowsNull()) {
                $arguments[] = null;
            } else {
                throw new RuntimeException(
                    sprintf('Cant instantiate service %s, cause of %s parameter', $id, $parameter->getName())
                );
            }
        }

        $className = $classSettings->getFqcn();

        return new $className(...$arguments);
    }

    private function getInstanceOfIterator(string $instanceOf): iterable
    {
        $classesWhoImplements = $this->interfaces[$instanceOf]
            ?? throw new RuntimeException("Nobody implements $instanceOf");

        foreach ($classesWhoImplements as $id) {
            yield $this->get($id);
        }
    }
}
