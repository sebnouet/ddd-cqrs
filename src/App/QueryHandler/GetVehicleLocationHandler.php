<?php

declare(strict_types=1);

namespace Fleet\App\QueryHandler;

use Fleet\App\Query\GetVehicleLocation;
use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Fleet\FleetRepository;
use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\VehicleRepository;

final readonly class GetVehicleLocationHandler
{
    public function __construct(
        private FleetRepository $fleetRepository,
        private VehicleRepository $vehicleRepository,
    ) {
    }

    public function __invoke(GetVehicleLocation $query): ?Location
    {
        $plateNumber = new PlateNumber($query->plateNumber);
        $fleet = $this->fleetRepository->get(new FleetId($query->fleetId));
        $fleet->assertHasVehicle($plateNumber);

        return $this->vehicleRepository->get($plateNumber)->location();
    }
}
