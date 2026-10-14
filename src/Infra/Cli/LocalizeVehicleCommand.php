<?php

declare(strict_types=1);

namespace Fleet\Infra\Cli;

use Fleet\App\Command\ParkVehicle;
use Fleet\App\CommandHandler\ParkVehicleHandler;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'localize-vehicle', description: 'Park a vehicle of a fleet at a location')]
final readonly class LocalizeVehicleCommand
{
    public function __construct(private ParkVehicleHandler $handler)
    {
    }

    public function __invoke(
        #[Argument] string $fleetId,
        #[Argument] string $vehiclePlateNumber,
        #[Argument] float $lat,
        #[Argument] float $lng,
        #[Argument] ?float $alt = null,
    ): int {
        ($this->handler)(new ParkVehicle($fleetId, $vehiclePlateNumber, $lat, $lng, $alt));

        return Command::SUCCESS;
    }
}
