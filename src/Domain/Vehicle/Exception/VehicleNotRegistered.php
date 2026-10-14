<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle\Exception;

use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Vehicle\PlateNumber;

final class VehicleNotRegistered extends \DomainException
{
    public function __construct(PlateNumber $plateNumber, FleetId $fleetId)
    {
        parent::__construct(sprintf('Vehicle "%s" is not registered into fleet "%s".', $plateNumber, $fleetId));
    }
}
