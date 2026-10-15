<?php

declare(strict_types=1);

namespace Fleet\Domain\Fleet;

final readonly class FleetId implements \Stringable
{
    public function __construct(public string $value)
    {
        if (!preg_match('/^[0-9a-f]{32}$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Invalid fleet id "%s".', $value));
        }
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(16)));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
