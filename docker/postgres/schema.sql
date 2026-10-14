CREATE TABLE fleet (
    id      CHAR(32) PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL
);

-- A vehicle has a single location, whatever the fleets it belongs to
CREATE TABLE vehicle (
    id           SERIAL PRIMARY KEY,
    plate_number VARCHAR(32) NOT NULL UNIQUE,
    latitude     DOUBLE PRECISION,
    longitude    DOUBLE PRECISION,
    altitude     DOUBLE PRECISION
);

CREATE TABLE fleet_vehicle (
    fleet_id   CHAR(32) NOT NULL REFERENCES fleet (id) ON DELETE CASCADE,
    vehicle_id INTEGER NOT NULL REFERENCES vehicle (id),
    PRIMARY KEY (fleet_id, vehicle_id)
);
