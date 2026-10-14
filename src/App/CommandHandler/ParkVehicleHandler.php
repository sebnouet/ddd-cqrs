<?php

declare(strict_types=1);

namespace Fleet\App\CommandHandler;

use Fleet\App\Command\ParkVehicle;
use Fleet\Domain\FleetId;
use Fleet\Domain\FleetRepository;
use Fleet\Domain\Location;
use Fleet\Domain\PlateNumber;

final readonly class ParkVehicleHandler
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }

    public function __invoke(ParkVehicle $command): void
    {
        $fleet = $this->fleetRepository->get(new FleetId($command->fleetId));
        $fleet->parkVehicle(
            new PlateNumber($command->plateNumber),
            new Location($command->latitude, $command->longitude),
        );
        $this->fleetRepository->save($fleet);
    }
}
