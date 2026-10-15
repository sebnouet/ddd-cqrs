<?php

declare(strict_types=1);

namespace Fleet\Tests\Unit\Domain;

use Fleet\Domain\Fleet\Fleet;
use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Fleet\UserId;
use Fleet\Domain\Vehicle\Exception\VehicleAlreadyRegistered;
use Fleet\Domain\Vehicle\Exception\VehicleNotRegistered;
use Fleet\Domain\Vehicle\PlateNumber;
use PHPUnit\Framework\TestCase;

final class FleetTest extends TestCase
{
    public function testItRegistersVehiclesWhateverThePlateNumberCase(): void
    {
        $fleet = new Fleet(FleetId::generate(), new UserId('user'));

        $fleet->registerVehicle(new PlateNumber('ff-123-ps'));

        self::assertTrue($fleet->hasVehicle(new PlateNumber('FF-123-PS')));
        self::assertFalse($fleet->hasVehicle(new PlateNumber('NT-987-IA')));
    }

    public function testItCannotRegisterTheSameVehicleTwice(): void
    {
        $fleet = new Fleet(FleetId::generate(), new UserId('user'), [new PlateNumber('FF-123-PS')]);

        $this->expectException(VehicleAlreadyRegistered::class);

        $fleet->registerVehicle(new PlateNumber('FF-123-PS'));
    }

    public function testItAssertsThatAVehicleIsRegistered(): void
    {
        $fleet = new Fleet(FleetId::generate(), new UserId('user'), [new PlateNumber('FF-123-PS')]);
        $fleet->assertHasVehicle(new PlateNumber('FF-123-PS'));

        $this->expectException(VehicleNotRegistered::class);

        $fleet->assertHasVehicle(new PlateNumber('NT-987-IA'));
    }

    public function testGeneratedIdsAreUniqueAndValid(): void
    {
        $id = FleetId::generate();

        self::assertNotEquals($id, FleetId::generate());
        self::assertEquals($id, new FleetId($id->value));
    }

    public function testItRejectsAMalformedId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FleetId('12');
    }
}
