DC = docker compose
PHP = $(DC) run --rm php

.PHONY: build up down db-reset install shell test phpunit behat phpstan cs cs-fix rector rector-fix qa

build:
	$(DC) build

up:
	$(DC) up -d postgres

down:
	$(DC) down

db-reset:
	$(DC) down -v
	$(DC) up -d --wait postgres

install:
	$(PHP) composer install

shell:
	$(PHP) sh

test: phpunit behat

phpunit:
	$(PHP) vendor/bin/phpunit

behat:
	$(PHP) vendor/bin/behat

phpstan:
	$(PHP) vendor/bin/phpstan analyse

cs:
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	$(PHP) vendor/bin/php-cs-fixer fix

rector:
	$(PHP) vendor/bin/rector --dry-run

rector-fix:
	$(PHP) vendor/bin/rector

qa: cs rector phpstan test
