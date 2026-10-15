<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle;

use Fleet\Domain\Vehicle\Exception\VehicleNotFound;

interface VehicleRepository
{
    /**
     * @throws VehicleNotFound
     */
    public function get(PlateNumber $plateNumber): Vehicle;

    public function find(PlateNumber $plateNumber): ?Vehicle;

    public function save(Vehicle $vehicle): void;
}
