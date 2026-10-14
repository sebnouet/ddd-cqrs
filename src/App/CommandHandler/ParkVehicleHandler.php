<?php

declare(strict_types=1);

namespace Fleet\App\CommandHandler;

use Fleet\App\Command\ParkVehicle;
use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Fleet\FleetRepository;
use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\Exception\VehicleNotRegistered;
use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\VehicleRepository;

final readonly class ParkVehicleHandler
{
    public function __construct(
        private FleetRepository $fleetRepository,
        private VehicleRepository $vehicleRepository,
    ) {
    }

    public function __invoke(ParkVehicle $command): void
    {
        $plateNumber = new PlateNumber($command->plateNumber);
        $fleet = $this->fleetRepository->get(new FleetId($command->fleetId));

        $vehicle = $fleet->hasVehicle($plateNumber) ? $this->vehicleRepository->find($plateNumber) : null;
        if (null === $vehicle) {
            throw new VehicleNotRegistered($plateNumber, $fleet->id);
        }

        $vehicle->park(new Location($command->latitude, $command->longitude, $command->altitude));
        $this->vehicleRepository->save($vehicle);
    }
}
