<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle;

use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\Exception\VehicleAlreadyParkedAtLocation;

final class Vehicle
{
    public function __construct(
        public readonly PlateNumber $plateNumber,
        private ?Location $location = null,
    ) {
    }

    public function location(): ?Location
    {
        return $this->location;
    }

    public function park(Location $location): void
    {
        if ($this->location?->equals($location)) {
            throw new VehicleAlreadyParkedAtLocation($this->plateNumber);
        }

        $this->location = $location;
    }
}
