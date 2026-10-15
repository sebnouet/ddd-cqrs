<?php

declare(strict_types=1);

namespace Fleet\Domain\Vehicle\Exception;

use Fleet\Domain\Vehicle\PlateNumber;

final class VehicleNotFound extends \DomainException
{
    public function __construct(PlateNumber $plateNumber)
    {
        parent::__construct(\sprintf('Vehicle "%s" not found.', $plateNumber));
    }
}
