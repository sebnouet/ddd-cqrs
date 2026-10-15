<?php

declare(strict_types=1);

namespace Fleet\Domain\Fleet;

use Fleet\Domain\Vehicle\Exception\VehicleAlreadyRegistered;
use Fleet\Domain\Vehicle\Exception\VehicleNotRegistered;
use Fleet\Domain\Vehicle\PlateNumber;

final class Fleet
{
    /** @var array<string, PlateNumber> reffers to Vehicle, indexed by plate number */
    private array $vehicles = [];

    /**
     * @param PlateNumber[] $vehicles
     */
    public function __construct(
        public readonly FleetId $id,
        public readonly UserId $userId,
        array $vehicles = [],
    ) {
        foreach ($vehicles as $plateNumber) {
            $this->vehicles[$plateNumber->value] = $plateNumber;
        }
    }

    public function registerVehicle(PlateNumber $plateNumber): void
    {
        if ($this->hasVehicle($plateNumber)) {
            throw new VehicleAlreadyRegistered($plateNumber, $this->id);
        }

        $this->vehicles[$plateNumber->value] = $plateNumber;
    }

    public function hasVehicle(PlateNumber $plateNumber): bool
    {
        return isset($this->vehicles[$plateNumber->value]);
    }

    /**
     * @return list<PlateNumber>
     */
    public function vehicles(): array
    {
        return array_values($this->vehicles);
    }

    public function assertHasVehicle(PlateNumber $plateNumber): void
    {
        if (!$this->hasVehicle($plateNumber)) {
            throw new VehicleNotRegistered($plateNumber, $this->id);
        }
    }
}
