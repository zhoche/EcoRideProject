CREATE TABLE avis (
  id             SERIAL PRIMARY KEY,
  ride_id        INT        NULL,
  driver_id      INT        NULL,
  passenger_id   INT        NULL,
  rating         INT        NOT NULL,
  comment        TEXT       NULL,
  status         VARCHAR(255) NULL,
  token          VARCHAR(255) NULL,
  is_validated   BOOLEAN    NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_avis_ride
    FOREIGN KEY (ride_id) REFERENCES ride(id) ON DELETE SET NULL,
  CONSTRAINT fk_avis_driver
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_avis_passenger
    FOREIGN KEY (passenger_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO avis (
  ride_id,
  driver_id,
  passenger_id,
  rating,
  comment,
  status,
  token,
  is_validated
) VALUES
  (
    (SELECT r.id FROM ride AS r WHERE r.departure = 'Paris' AND r.arrival = 'Lille' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'driver1@example.com' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'passenger@example.com' LIMIT 1),
    5,
    'Super trajet, merci !',
    'validé',
    '117e9f0dee2682b6',
    TRUE
  ),
  (
    (SELECT r.id FROM ride AS r WHERE r.departure = 'Lyon' AND r.arrival = 'Grenoble' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'driver2@example.com' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'passenger2@example.com' LIMIT 1),
    4,
    'Conduite agréable.',
    'à traiter',
    '7d353584e102cde4',
    FALSE
  ),
  (
    (SELECT r.id FROM ride AS r WHERE r.departure = 'Bordeaux' AND r.arrival = 'Toulouse' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'driver1@example.com' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'passenger@example.com' LIMIT 1),
    3,
    'Un peu de retard, mais sympa.',
    'refusé',
    'bbc29648bbdebabc',
    FALSE
  ),
  (
    (SELECT r.id FROM ride AS r WHERE r.departure = 'Nantes' AND r.arrival = 'Rennes' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'driver3@example.com' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'passenger2@example.com' LIMIT 1),
    5,
    'Très bonne ambiance.',
    'validé',
    'bb26de9c2089698f',
    TRUE
  ),
  (
    (SELECT r.id FROM ride AS r WHERE r.departure = 'Nice' AND r.arrival = 'Marseille' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'driver2@example.com' LIMIT 1),
    (SELECT u.id FROM users AS u WHERE u.email = 'passenger@example.com' LIMIT 1),
    4,
    'Voiture propre et confortable.',
    'en attente',
    '2f0607cb1c14c490',
    FALSE
  );
