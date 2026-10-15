# DDD-CQRS test - Vehicle fleet parking management

Vehicle and fleet management - built on DDD and CQRS

## Prerequisites

- Docker and Docker Compose
- `make`

## Installation

```bash
make build     # build image
make up        # start postgresql and create the databases
make install   # install dependencies
```
The schema is set only on the first start of PostgreSQL. `make db-reset` can be used to reset all data.

## Use

Commands must be run in the php container. Use `make shell` to enter the container.

```bash
docker compose run --rm php ./fleet create <userId>
docker compose run --rm php ./fleet register-vehicle <fleetId> <vehiclePlateNumber>
docker compose run --rm php ./fleet localize-vehicle <fleetId> <vehiclePlateNumber> <lat> <lng> [alt]
```

Example (with no shell):

```bash
FLEET_ID=$(docker compose run --rm -T php ./fleet create alice)
docker compose run --rm php ./fleet register-vehicle "$FLEET_ID" AB-123-CD
docker compose run --rm php ./fleet localize-vehicle "$FLEET_ID" AB-123-CD 43.296482 5.36978 12
```

## Architecture

```
src/
├── App/      # Commands, queries and handlers
├── Domain/   # Entities, value objects, domain exceptions and repositories interfaces
└── Infra/    # command lines (Symfony Console), repositories
```

## Tests

```bash
make test      # PHPUnit and Behat
make phpunit   # unit tests with PHPUnit
make behat     # acceptance tests
```

Behat uses two suites in `behat.yml` :

- `memory` : all scenarios with in memory repositories
- `postgres` : only `@critical` scenarios, on real database `fleet_test`.

To run only one : `docker compose run --rm php vendor/bin/behat --suite=memory`.

## Quality

My favorite tools and their use : 

| Tool | Job | Command |
|---|---|---|
| [PHPStan](https://phpstan.org/) | Static analysis. | `make phpstan` |
| [PHP-CS-Fixer](https://cs.symfony.com/) | Apply code style rules. | `make cs` / `make cs-fix` |
| [Rector](https://getrector.com/) | Modernize and simplify code. Can be use for upgrades. | `make rector` / `make rector-fix` |
| [PHPUnit](https://phpunit.de/) | Unit testing | `make phpunit` |

- Phpstan to ensure code quality
- Php cs Fixer to ensure code style is the same for all
- Rector checks code quality and helps to upgrade and modernize
- Phpunit to create and run unit tests


`make qa` launches all checks (style, Rector, PHPStan, PHPUnit, Behat).

## CI/CD
### Continuous Integration

A pipeline (my favorite tools are TeamCity, GitHub Actions, GitLab CI) runs on every pull request and every push to `main`:

1. **Preparation**: PHP 8.3 with `pdo_pgsql` (same Docker image as local), PostgreSQL service,
   `composer install` with dependency caching, creation of the `fleet_test` schema.
2. **Quick checks**: `composer validate`, `php-cs-fixer --dry-run`, `rector --dry-run`, `phpstan`.
3. **Tests**: PHPUnit, followed by Behat (the `memory` suite and the `postgres` suite running against the actual database).

This is an equivalent to `make qa`. Quick checks run first to allow for "failing fast,"
without waiting for the full test suite to complete.


### Continuous Deployment

This has been intentionally omitted. I am not a big fan of continuous deployment, but if needed, a Git tag could trigger the build and publication
of the Docker image.