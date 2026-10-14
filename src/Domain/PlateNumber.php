<?php

declare(strict_types=1);

namespace Fleet\Domain;

final readonly class PlateNumber
{
    public string $value;

    public function __construct(string $value)
    {
        $value = strtoupper(trim($value));
        if ('' === $value) {
            throw new \InvalidArgumentException('Plate number cannot be empty.');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
