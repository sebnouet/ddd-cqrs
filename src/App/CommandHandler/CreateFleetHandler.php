<?php

declare(strict_types=1);

namespace Fleet\App\CommandHandler;

use Fleet\App\Command\CreateFleet;
use Fleet\Domain\Fleet;
use Fleet\Domain\FleetRepository;
use Fleet\Domain\UserId;

final readonly class CreateFleetHandler
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }

    public function __invoke(CreateFleet $command): string
    {
        $fleet = Fleet::create(new UserId($command->userId));
        $this->fleetRepository->save($fleet);

        return $fleet->id->value;
    }
}
