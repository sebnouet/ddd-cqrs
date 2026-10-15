<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle\Exception;

use Fleet\Domain\Vehicle\PlateNumber;

final class VehicleAlreadyParkedAtLocation extends \DomainException
{
    public function __construct(PlateNumber $plateNumber)
    {
        parent::__construct(sprintf('Vehicle "%s" is already parked at this location.', $plateNumber));
    }
}
