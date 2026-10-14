DC = docker compose
PHP = $(DC) run --rm php

.PHONY: build up down install shell test

build:
	$(DC) build

up:
	$(DC) up -d postgres

down:
	$(DC) down

install:
	$(PHP) composer install

shell:
	$(PHP) sh

test:
	$(PHP) vendor/bin/behat
