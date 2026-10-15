<?php

declare(strict_types=1);

namespace Fleet\Infra\Repository;

use Fleet\Domain\Fleet\Exception\FleetNotFound;
use Fleet\Domain\Fleet\Fleet;
use Fleet\Domain\Fleet\FleetId;
use Fleet\Domain\Fleet\FleetRepository;
use Fleet\Domain\Fleet\UserId;
use Fleet\Domain\Vehicle\PlateNumber;

final readonly class PostgresFleetRepository implements FleetRepository
{
    public function __construct(private \PDO $connection)
    {
    }

    public function get(FleetId $id): Fleet
    {
        $statement = $this->connection->prepare('SELECT user_id FROM fleet WHERE id = :id');
        $statement->execute(['id' => $id->value]);
        /** @var string|false $userId */
        $userId = $statement->fetchColumn();
        if (false === $userId) {
            throw new FleetNotFound($id);
        }

        $statement = $this->connection->prepare(
            '
                SELECT vehicle.plate_number
                FROM fleet_vehicle
                JOIN vehicle ON vehicle.id = fleet_vehicle.vehicle_id
                WHERE fleet_vehicle.fleet_id = :id'
        );
        $statement->execute(['id' => $id->value]);
        /** @var list<string> $plateNumbers */
        $plateNumbers = $statement->fetchAll(\PDO::FETCH_COLUMN);

        $vehicles = array_map(
            static fn (string $plateNumber): PlateNumber => new PlateNumber($plateNumber),
            $plateNumbers,
        );

        return new Fleet($id, new UserId($userId), $vehicles);
    }

    public function save(Fleet $fleet): void
    {
        $this->connection->beginTransaction();

        try {
            $this->connection
                ->prepare('INSERT INTO fleet (id, user_id) VALUES (:id, :user_id) ON CONFLICT (id) DO NOTHING')
                ->execute(['id' => $fleet->id->value, 'user_id' => $fleet->userId->value]);

            $statement = $this->connection->prepare('INSERT INTO fleet_vehicle (fleet_id, vehicle_id)
                SELECT :fleet_id, id FROM vehicle WHERE plate_number = :plate_number
                ON CONFLICT DO NOTHING');

            foreach ($fleet->vehicles() as $plateNumber) {
                $statement->execute(['fleet_id' => $fleet->id->value, 'plate_number' => $plateNumber->value]);
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
