<?php

declare(strict_types=1);

namespace Fleet\Infra\Factory;

final class PostgresConnectionFactory
{
    public static function fromEnvironment(string $dsnVariable = 'DATABASE_DSN'): \PDO
    {
        return new \PDO(
            self::env($dsnVariable),
            self::env('DATABASE_USER'),
            self::env('DATABASE_PASSWORD'),
            [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC],
        );
    }

    private static function env(string $name): string
    {
        $value = getenv($name);
        if (false === $value) {
            throw new \RuntimeException(\sprintf('Environment variable "%s" is not defined.', $name));
        }

        return $value;
    }
}
