<?php

declare(strict_types=1);

namespace Fleet\App\CommandHandler;

use Fleet\App\Command\RegisterVehicle;
use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Fleet\FleetRepository;
use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\Vehicle;
use Fleet\Domain\Vehicle\VehicleRepository;

final readonly class RegisterVehicleHandler
{
    public function __construct(
        private FleetRepository $fleetRepository,
        private VehicleRepository $vehicleRepository,
    ) {
    }

    public function __invoke(RegisterVehicle $command): void
    {
        $plateNumber = new PlateNumber($command->plateNumber);
        $fleet = $this->fleetRepository->get(new FleetId($command->fleetId));

        if (!$this->vehicleRepository->find($plateNumber) instanceof Vehicle) {
            $this->vehicleRepository->save(new Vehicle($plateNumber));
        }

        $fleet->registerVehicle($plateNumber);
        $this->fleetRepository->save($fleet);
    }
}
