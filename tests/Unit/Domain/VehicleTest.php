<?php

declare(strict_types=1);

namespace Fleet\Tests\Unit\Domain;

use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\Exception\VehicleAlreadyParkedAtLocation;
use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\Vehicle;
use PHPUnit\Framework\TestCase;

final class VehicleTest extends TestCase
{
    public function testANewVehicleHasNoKnownLocation(): void
    {
        self::assertNull((new Vehicle(new PlateNumber('FF-123-RR')))->location());
    }

    public function testItCanBeParkedAtAnotherLocation(): void
    {
        $vehicle = new Vehicle(new PlateNumber('FF-123-RR'), new Location(43.29, 5.37));

        $vehicle->park(new Location(48.85, 2.35));

        self::assertEquals(new Location(48.85, 2.35), $vehicle->location());
    }

    public function testItCannotBeParkedTwiceAtTheSameLocation(): void
    {
        $vehicle = new Vehicle(new PlateNumber('FF-123-RR'), new Location(43.29, 5.37));

        $this->expectException(VehicleAlreadyParkedAtLocation::class);

        $vehicle->park(new Location(43.29, 5.37));
    }
}
