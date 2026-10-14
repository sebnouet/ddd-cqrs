<?php

declare(strict_types=1);

namespace Fleet\App\QueryHandler;

use Fleet\App\Query\GetVehicleLocation;
use Fleet\Domain\FleetId;
use Fleet\Domain\FleetRepository;
use Fleet\Domain\Location;
use Fleet\Domain\PlateNumber;

final readonly class GetVehicleLocationHandler
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }

    public function __invoke(GetVehicleLocation $query): ?Location
    {
        return $this->fleetRepository
            ->get(new FleetId($query->fleetId))
            ->locationOf(new PlateNumber($query->plateNumber));
    }
}
