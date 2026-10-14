<?php

declare(strict_types=1);

namespace Fleet\Domain\Fleet\Exception;

use Fleet\Domain\FleetId;

final class FleetNotFound extends \DomainException
{
    public function __construct(FleetId $fleetId)
    {
        parent::__construct(sprintf('Fleet "%s" not found.', $fleetId));
    }
}
