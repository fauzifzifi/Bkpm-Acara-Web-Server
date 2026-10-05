<?php
namespace App\Core;

use PDO;
use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

/**
 * Dependency Injection sederhana.
 * Membuat objek dan otomatis menyuntikkan dependency yang diminta constructor-nya:
 *
 *   MahasiswaController(MahasiswaRepository $repo)
 *     -> MahasiswaRepository(PDO $pdo)            (diwariskan dari BaseModel)
 *          -> Database::getInstance()->getConnection()
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

            if ($dependency === PDO::class) {
                $dependencies[] = Database::getInstance()->getConnection();
            } elseif ($dependency === Database::class) {
                $dependencies[] = Database::getInstance();
            } else {
                $dependencies[] = self::make($dependency);
            }
        }

        return $ref->newInstanceArgs($dependencies);
    }
}
