<?php
namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

/**
 * Dependency Injection sederhana.
 * Membuat objek dan otomatis menyuntikkan dependency yang diminta constructor-nya:
 *
 *   MahasiswaController(MahasiswaRepository $repo)
 *     -> MahasiswaRepository(Database $db)
 *          -> Database::getInstance()
 *
 * Sehingga Controller tidak perlu membuat Repository / koneksi sendiri.
 */
class Container
{
    public static function make(string $class): object
    {
        $ref  = new ReflectionClass($class);
        $ctor = $ref->getConstructor();

        if ($ctor === null) {
            return new $class();
        }

        $dependencies = [];
        foreach ($ctor->getParameters() as $param) {
            $type = $param->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new RuntimeException(
                    "Parameter \${$param->getName()} pada {$class} tidak bisa di-inject otomatis."
                );
            }

            $dependency = $type->getName();
            $dependencies[] = ($dependency === Database::class)
                ? Database::getInstance()          // Database memakai singleton
                : self::make($dependency);
        }

        return $ref->newInstanceArgs($dependencies);
    }
}
