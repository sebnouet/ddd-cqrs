<?php

declare(strict_types=1);

namespace Fleet\Domain;

final readonly class FleetId
{
    public function __construct(public string $value)
    {
        if ('' === trim($value)) {
            throw new \InvalidArgumentException('Fleet id cannot be empty.');
        }
    }

    public static function generate(): self
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0F | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3F | 0x80);

        return new self(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4)));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
