<?php

declare(strict_types=1);

namespace Fleet\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Fleet\App\Command\CreateFleet;
use Fleet\App\Command\ParkVehicle;
use Fleet\App\Command\RegisterVehicle;
use Fleet\App\CommandHandler\CreateFleetHandler;
use Fleet\App\CommandHandler\ParkVehicleHandler;
use Fleet\App\CommandHandler\RegisterVehicleHandler;
use Fleet\App\Query\GetVehicleLocation;
use Fleet\App\Query\IsVehicleRegistered;
use Fleet\App\QueryHandler\GetVehicleLocationHandler;
use Fleet\App\QueryHandler\IsVehicleRegisteredHandler;
use Fleet\Domain\Fleet\FleetRepository;
use Fleet\Domain\Location\Exception\VehicleAlreadyParkedAtLocation;
use Fleet\Domain\Location\Location;
use Fleet\Domain\Vehicle\Exception\VehicleAlreadyRegistered;
use Fleet\Domain\Vehicle\VehicleRepository;
use Fleet\Infra\Factory\PostgresConnectionFactory;
use Fleet\Infra\Repository\MemoryFleetRepository;
use Fleet\Infra\Repository\MemoryVehicleRepository;
use Fleet\Infra\Repository\PostgresFleetRepository;
use Fleet\Infra\Repository\PostgresVehicleRepository;
use Throwable;

final class FeatureContext implements Context
{
    private readonly FleetRepository $fleetRepository;
    private readonly VehicleRepository $vehicleRepository;

    private string $myFleetId;
    private string $otherFleetId;
    private string $plateNumber;
    private Location $location;
    private ?Throwable $caughtException = null;

    public function __construct(string $infrastructure = 'memory')
    {
        if ('postgres' === $infrastructure) {
            $connection = PostgresConnectionFactory::fromEnvironment('DATABASE_TEST_DSN');
            $connection->exec('TRUNCATE fleet, vehicle CASCADE');
            $this->fleetRepository = new PostgresFleetRepository($connection);
            $this->vehicleRepository = new PostgresVehicleRepository($connection);
        } else {
            $this->fleetRepository = new MemoryFleetRepository();
            $this->vehicleRepository = new MemoryVehicleRepository();
        }
    }

    #[Given('my fleet')]
    public function myFleet(): void
    {
        $this->myFleetId = $this->createFleet('my-user');
    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {
        $this->otherFleetId = $this->createFleet('another-user');
    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {
        $this->plateNumber = 'SN-4242-JJ';
    }

    #[Given('a location')]
    public function aLocation(): void
    {
        $this->location = new Location(43.373522, 5.504592, 152.5);
    }

    #[Given('I have registered this vehicle into my fleet')]
    #[When('I register this vehicle into my fleet')]
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $this->registerVehicle($this->myFleetId);
    }

    #[Given("this vehicle has been registered into the other user's fleet")]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $this->registerVehicle($this->otherFleetId);
    }

    #[When('I try to register this vehicle into my fleet')]
    public function iTryToRegisterThisVehicleIntoMyFleet(): void
    {
        try {
            $this->registerVehicle($this->myFleetId);
        } catch (Throwable $e) {
            $this->caughtException = $e;
        }
    }

    #[Given('my vehicle has been parked into this location')]
    #[When('I park my vehicle at this location')]
    public function iParkMyVehicleAtThisLocation(): void
    {
        $this->parkVehicle();
    }

    #[When('I try to park my vehicle at this location')]
    public function iTryToParkMyVehicleAtThisLocation(): void
    {
        try {
            $this->parkVehicle();
        } catch (Throwable $e) {
            $this->caughtException = $e;
        }
    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        $handler = new IsVehicleRegisteredHandler($this->fleetRepository);
        $command = new IsVehicleRegistered($this->myFleetId, $this->plateNumber);
        $isRegistered = $handler($command);

        self::assert($isRegistered, 'The vehicle is not part of my fleet.');
    }

    #[Then('I should be informed that this vehicle has already been registered into my fleet')]
    public function iShouldBeInformedThatVehicleHasAlreadyBeenRegistered(): void
    {
        self::assert(
            $this->caughtException instanceof VehicleAlreadyRegistered,
            'Expected a VehicleAlreadyRegistered exception.',
        );
    }

    #[Then('the known location of my vehicle should verify this location')]
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $handler = new GetVehicleLocationHandler($this->fleetRepository, $this->vehicleRepository);
        $command = new GetVehicleLocation($this->myFleetId, $this->plateNumber);
        $knownLocation = $handler($command);

        self::assert(
            $knownLocation?->equals($this->location) === true,
            'The known location of my vehicle does not match.',
        );
    }

    #[Then('I should be informed that my vehicle is already parked at this location')]
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        self::assert(
            $this->caughtException instanceof VehicleAlreadyParkedAtLocation,
            'Expected a VehicleAlreadyParkedAtLocation exception.',
        );
    }

    private function createFleet(string $userId): string
    {
        $handler = new CreateFleetHandler($this->fleetRepository);
        $command = new CreateFleet($userId);
        return $handler($command);
    }

    private function registerVehicle(string $fleetId): void
    {
        $handler = new RegisterVehicleHandler($this->fleetRepository, $this->vehicleRepository);
        $command = new RegisterVehicle($fleetId, $this->plateNumber);
        $handler($command);
    }

    private function parkVehicle(): void
    {
        $handler = new ParkVehicleHandler($this->fleetRepository, $this->vehicleRepository);
        $command = new ParkVehicle(
            $this->myFleetId,
            $this->plateNumber,
            $this->location->latitude,
            $this->location->longitude,
            $this->location->altitude,
        );
        $handler($command);
    }

    private static function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException($message);
        }
    }
}
