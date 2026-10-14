<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle;

interface VehicleRepository
{
    public function find(PlateNumber $plateNumber): ?Vehicle;

    public function save(Vehicle $vehicle): void;
}
