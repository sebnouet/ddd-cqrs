<?php

declare(strict_types=1);

namespace Fleet\App\CommandHandler;

use Fleet\App\Command\RegisterVehicle;
use Fleet\Domain\FleetId;
use Fleet\Domain\FleetRepository;
use Fleet\Domain\PlateNumber;

final readonly class RegisterVehicleHandler
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }

    public function __invoke(RegisterVehicle $command): void
    {
        $fleet = $this->fleetRepository->get(new FleetId($command->fleetId));
        $fleet->registerVehicle(new PlateNumber($command->plateNumber));
        $this->fleetRepository->save($fleet);
    }
}
