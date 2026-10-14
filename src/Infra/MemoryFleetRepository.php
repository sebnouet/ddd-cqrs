<?php

declare(strict_types=1);

namespace Fleet\Infra;

use Fleet\Domain\Fleet;
use Fleet\Domain\Fleet\Exception\FleetNotFound;
use Fleet\Domain\FleetId;
use Fleet\Domain\FleetRepository;

final class MemoryFleetRepository implements FleetRepository
{
    /** @var array<string, Fleet> */
    private array $fleets = [];

    public function get(FleetId $id): Fleet
    {
        // fake db storage
        return clone ($this->fleets[$id->value] ?? throw new FleetNotFound($id));
    }

    public function save(Fleet $fleet): void
    {
        $this->fleets[$fleet->id->value] = clone $fleet;
    }
}
