<?php

declare(strict_types=1);

namespace Fleet\App\QueryHandler;

use Fleet\App\Query\IsVehicleRegistered;
use Fleet\Domain\FleetId;
use Fleet\Domain\FleetRepository;
use Fleet\Domain\PlateNumber;

final readonly class IsVehicleRegisteredHandler
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }

    public function __invoke(IsVehicleRegistered $query): bool
    {
        return $this->fleetRepository
            ->get(new FleetId($query->fleetId))
            ->hasVehicle(new PlateNumber($query->plateNumber));
    }
}
