<?php

declare(strict_types=1);

namespace Fleet\Infra\Repository;

use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\PlateNumber;
use Fleet\Domain\Vehicle\Vehicle;
use Fleet\Domain\Vehicle\VehicleRepository;

final readonly class PostgresVehicleRepository implements VehicleRepository
{
    public function __construct(private \PDO $connection)
    {
    }

    public function find(PlateNumber $plateNumber): ?Vehicle
    {
        $statement = $this->connection->prepare(
            'SELECT latitude, longitude, altitude FROM vehicle WHERE plate_number = :plate_number',
        );
        $statement->execute(['plate_number' => $plateNumber->value]);
        $row = $statement->fetch();
        if (false === $row) {
            return null;
        }

        return new Vehicle($plateNumber, null === $row['latitude'] ? null : new Location(
            (float)$row['latitude'],
            (float)$row['longitude'],
            null === $row['altitude'] ? null : (float)$row['altitude'],
        ));
    }

    public function save(Vehicle $vehicle): void
    {
        $this->connection->prepare(<<<'SQL'
            INSERT INTO vehicle (plate_number, latitude, longitude, altitude)
            VALUES (:plate_number, :latitude, :longitude, :altitude)
            ON CONFLICT (plate_number)
            DO UPDATE SET latitude = EXCLUDED.latitude, longitude = EXCLUDED.longitude, altitude = EXCLUDED.altitude
            SQL
        )->execute([
            'plate_number' => $vehicle->plateNumber->value,
            'latitude' => $vehicle->location()?->latitude,
            'longitude' => $vehicle->location()?->longitude,
            'altitude' => $vehicle->location()?->altitude,
        ]);
    }
}
