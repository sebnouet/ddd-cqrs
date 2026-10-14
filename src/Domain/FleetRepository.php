<?php

declare(strict_types=1);

namespace Fleet\Domain;

use Fleet\Domain\Fleet\Exception\FleetNotFound;

interface FleetRepository
{
    /**
     * @throws FleetNotFound
     */
    public function get(FleetId $id): Fleet;

    public function save(Fleet $fleet): void;
}
