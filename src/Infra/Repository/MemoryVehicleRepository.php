<?php

declare(strict_types=1);

namespace Fleet\Infra\Repository;

use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\Vehicle;
use Fleet\Domain\Vehicle\VehicleRepository;

final class MemoryVehicleRepository implements VehicleRepository
{
    /** @var array<string, Vehicle> */
    private array $vehicles = [];

    public function find(PlateNumber $plateNumber): ?Vehicle
    {
        $vehicle = $this->vehicles[$plateNumber->value] ?? null;

        return null === $vehicle ? null : clone $vehicle;
    }

    public function save(Vehicle $vehicle): void
    {
        $this->vehicles[$vehicle->plateNumber->value] = clone $vehicle;
    }
}
