<?php

declare(strict_types=1);

namespace Fleet\Domain\Location\Exception;

use Fleet\Domain\PlateNumber;

final class VehicleAlreadyParkedAtLocation extends \DomainException
{
    public function __construct(PlateNumber $plateNumber)
    {
        parent::__construct(sprintf('Vehicle "%s" is already parked at this location.', $plateNumber));
    }
}
