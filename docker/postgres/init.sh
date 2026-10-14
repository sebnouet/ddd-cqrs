#!/bin/sh

set -e

psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -c 'CREATE DATABASE fleet_test'

for db in "$POSTGRES_DB" fleet_test; do
    psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$db" -f /schema.sql
done
