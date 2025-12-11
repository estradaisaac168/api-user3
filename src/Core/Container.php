<?php

namespace App\Core;

class Container
{
    protected static array $instances = [];

    public static function resolve(string $class)
    {
        // Si ya existe una instancia, devolverla (singleton simple)
        if (isset(self::$instances[$class])) {
            return self::$instances[$class];
        }

        $reflectionClass = new \ReflectionClass($class);

        // Si no tiene constructor, instanciar sin dependencias
        if (!$constructor = $reflectionClass->getConstructor()) {
            $object = new $class();
            self::$instances[$class] = $object;
            return $object;
        }

        // Resolver dependencias del constructor
        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {

            $type = $param->getType();

            if (!$type) {
                throw new \Exception(
                    "No se puede resolver la dependencia \${$param->getName()} en {$class}"
                );
            }

            $dependencyClass = $type->getName();

            // Resolver la dependencia recursivamente
            $dependencies[] = self::resolve($dependencyClass);
        }

        // Crear la instancia con todas las dependencias
        $object = new $class(...$dependencies);
        self::$instances[$class] = $object;

        return $object;
    }
}

