<?php

declare(strict_types=1);

namespace Fleet\Domain;

use Fleet\Domain\Vehicle\Exception\VehicleAlreadyRegistered;
use Fleet\Domain\Vehicle\Exception\VehicleNotRegistered;

final class Fleet
{
    /** @var array<string, Vehicle> indexed by plate number */
    private array $vehicles = [];

    /**
     * @param Vehicle[] $vehicles
     */
    public function __construct(
        public readonly FleetId $id,
        public readonly UserId $userId,
        array $vehicles = [],
    ) {
        foreach ($vehicles as $vehicle) {
            $this->vehicles[$vehicle->plateNumber->value] = $vehicle;
        }
    }

    public static function create(UserId $userId): self
    {
        return new self(FleetId::generate(), $userId);
    }

    public function registerVehicle(PlateNumber $plateNumber): void
    {
        if ($this->hasVehicle($plateNumber)) {
            throw new VehicleAlreadyRegistered($plateNumber, $this->id);
        }

        $this->vehicles[$plateNumber->value] = new Vehicle($plateNumber);
    }

    public function parkVehicle(PlateNumber $plateNumber, Location $location): void
    {
        $this->vehicle($plateNumber)->park($location);
    }

    public function hasVehicle(PlateNumber $plateNumber): bool
    {
        return isset($this->vehicles[$plateNumber->value]);
    }

    public function locationOf(PlateNumber $plateNumber): ?Location
    {
        return $this->vehicle($plateNumber)->location();
    }

    /**
     * @return list<Vehicle>
     */
    public function vehicles(): array
    {
        return array_values($this->vehicles);
    }

    private function vehicle(PlateNumber $plateNumber): Vehicle
    {
        return $this->vehicles[$plateNumber->value]
            ?? throw new VehicleNotRegistered($plateNumber, $this->id);
    }

    public function __clone()
    {
        $this->vehicles = array_map(static fn (Vehicle $vehicle) => clone $vehicle, $this->vehicles);
    }
}
