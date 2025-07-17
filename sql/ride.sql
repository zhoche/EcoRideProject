CREATE TABLE ride (
  id              SERIAL PRIMARY KEY,
  driver_id       INT        NOT NULL,
  departure       VARCHAR(255) NOT NULL,
  arrival         VARCHAR(255) NOT NULL,
  date            TIMESTAMP  NOT NULL,
  available_seats INT        NOT NULL,
  price           NUMERIC(5,2) NOT NULL,
  vehicle_id      INT        NOT NULL,
  initial_seats   INT        NOT NULL,
  extras          VARCHAR(255),
  status          VARCHAR(20) NOT NULL DEFAULT 'en cours',
  CONSTRAINT fk_ride_driver
    FOREIGN KEY(driver_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_ride_vehicle
    FOREIGN KEY(vehicle_id) REFERENCES vehicle(id) ON DELETE CASCADE
);

INSERT INTO ride (
  driver_id, departure, arrival, date,
  available_seats, price, vehicle_id, initial_seats,
  extras, status
) VALUES
  (
    (SELECT id FROM users WHERE email = 'driver1@example.com' LIMIT 1),
    'Paris', 'Lille',
    NOW() + INTERVAL '1 day',
    3, 5,
    (SELECT id FROM vehicle WHERE plate_number = 'ABC-123-EF' LIMIT 1),
    5,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver2@example.com' LIMIT 1),
    'Lyon', 'Grenoble',
    NOW() + INTERVAL '2 days',
    4, 4,
    (SELECT id FROM vehicle WHERE plate_number = 'GH-456-IJ' LIMIT 1),
    4,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver1@example.com' LIMIT 1),
    'Bordeaux', 'Toulouse',
    NOW() + INTERVAL '3 days',
    2, 6,
    (SELECT id FROM vehicle WHERE plate_number = 'EV-789-KL' LIMIT 1),
    3,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver3@example.com' LIMIT 1),
    'Nantes', 'Rennes',
    NOW() + INTERVAL '4 days',
    3, 3,
    (SELECT id FROM vehicle WHERE plate_number = 'MN-012-OP' LIMIT 1),
    5,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver2@example.com' LIMIT 1),
    'Nice', 'Marseille',
    NOW() + INTERVAL '5 days',
    5, 3,
    (SELECT id FROM vehicle WHERE plate_number = 'VW-333-ID3' LIMIT 1),
    5,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver1@example.com' LIMIT 1),
    'Amiens', 'Rouen',
    NOW() + INTERVAL '6 days',
    1, 5,
    (SELECT id FROM vehicle WHERE plate_number = 'ABC-123-EF' LIMIT 1),
    4,
    'Trajet convivial avec pauses', 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver3@example.com' LIMIT 1),
    'Dijon', 'Strasbourg',
    NOW() + INTERVAL '7 days',
    2, 4,
    (SELECT id FROM vehicle WHERE plate_number = 'MN-012-OP' LIMIT 1),
    5,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver1@example.com' LIMIT 1),
    'Tours', 'Orléans',
    NOW() + INTERVAL '8 days',
    4, 3,
    (SELECT id FROM vehicle WHERE plate_number = 'EV-789-KL' LIMIT 1),
    5,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver2@example.com' LIMIT 1),
    'Avignon', 'Montpellier',
    NOW() + INTERVAL '9 days',
    3, 6,
    (SELECT id FROM vehicle WHERE plate_number = 'VW-333-ID3' LIMIT 1),
    4,
    NULL, 'en cours'
  ),
  (
    (SELECT id FROM users WHERE email = 'driver3@example.com' LIMIT 1),
    'Clermont-Ferrand', 'Saint-Étienne',
    NOW() + INTERVAL '10 days',
    2, 5,
    (SELECT id FROM vehicle WHERE plate_number = 'MN-012-OP' LIMIT 1),
    5,
    NULL, 'en cours'
  );
