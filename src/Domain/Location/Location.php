<?php

declare(strict_types=1);

namespace Fleet\Domain\Location;

final readonly class Location
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?float $altitude = null,
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException(\sprintf('Invalid latitude "%s".', $latitude));
        }
        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException(\sprintf('Invalid longitude "%s".', $longitude));
        }
    }

    public function equals(self $other): bool
    {
        return $this->latitude === $other->latitude
            && $this->longitude === $other->longitude
            && $this->altitude === $other->altitude;
    }
}
