DC = docker compose
PHP = $(DC) run --rm php

.PHONY: build up down db-reset install shell test

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

test:
	$(PHP) vendor/bin/behat
