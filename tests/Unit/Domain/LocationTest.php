<?php

declare(strict_types=1);

namespace Fleet\Tests\Unit\Domain;

use Fleet\Domain\Location\Location;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocationTest extends TestCase
{
    /**
     * @return iterable<string, array{float, float}>
     */
    public static function invalidCoordinates(): iterable
    {
        yield 'latitude too low' => [-90.1, 0];
        yield 'latitude too high' => [90.1, 0];
        yield 'longitude too low' => [0, -180.1];
        yield 'longitude too high' => [0, 180.1];
    }

    #[DataProvider('invalidCoordinates')]
    public function testItRejectsCoordinatesOutsideEarth(float $latitude, float $longitude): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Location($latitude, $longitude);
    }

    public function testAltitudeIsPartOfEquality(): void
    {
        $location = new Location(43.29, 5.37, 12);

        self::assertTrue($location->equals(new Location(43.29, 5.37, 12)));
        self::assertFalse($location->equals(new Location(43.29, 5.37, 13)));
        self::assertFalse($location->equals(new Location(43.29, 5.37)));
    }
}
