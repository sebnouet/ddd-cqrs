<?php

declare(strict_types=1);

namespace Fleet\Infra\Cli;

use Fleet\App\Command\RegisterVehicle;
use Fleet\App\CommandHandler\RegisterVehicleHandler;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'register-vehicle', description: 'Register a vehicle into a fleet')]
final readonly class RegisterVehicleCommand
{
    public function __construct(private RegisterVehicleHandler $handler)
    {
    }

    public function __invoke(#[Argument] string $fleetId, #[Argument] string $vehiclePlateNumber): int
    {
        ($this->handler)(new RegisterVehicle($fleetId, $vehiclePlateNumber));

        return Command::SUCCESS;
    }
}
