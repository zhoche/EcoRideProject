CREATE TABLE vehicle (
  id           SERIAL PRIMARY KEY,
  owner_id     INT NOT NULL,
  brand        VARCHAR(255) NOT NULL,
  model        VARCHAR(255) NOT NULL,
  plate_number VARCHAR(50)  NOT NULL,
  is_electric  BOOLEAN      NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_vehicle_owner
    FOREIGN KEY (owner_id)
      REFERENCES users(id)
      ON DELETE CASCADE
);

INSERT INTO vehicle (owner_id, brand, model, plate_number, is_electric)
VALUES
  (
    (SELECT id FROM users WHERE email = 'driver1@example.com' LIMIT 1),
    'Toyota',    'Corolla',  'ABC-123-EF',  FALSE
  ),
  (
    (SELECT id FROM users WHERE email = 'driver2@example.com' LIMIT 1),
    'Peugeot',   '208',      'GH-456-IJ',   FALSE
  ),
  (
    (SELECT id FROM users WHERE email = 'driver3@example.com' LIMIT 1),
    'Tesla',     'Model 3',  'EV-789-KL',   TRUE
  ),
  (
    (SELECT id FROM users WHERE email = 'driver4@example.com' LIMIT 1),
    'Renault',   'Clio',     'MN-012-OP',   FALSE
  ),
  (
    (SELECT id FROM users WHERE email = 'driver5@example.com' LIMIT 1),
    'Volkswagen','ID 3',     'VW-333-ID3',  TRUE
  );
