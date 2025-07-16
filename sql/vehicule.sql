CREATE TABLE `vehicle` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` INT UNSIGNED NOT NULL,
  `brand` VARCHAR(255) NOT NULL,
  `model` VARCHAR(255) NOT NULL,
  `plate_number` VARCHAR(50) NOT NULL,
  `is_electric` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `IDX_VEHICLE_OWNER` (`owner_id`),
  CONSTRAINT `FK_VEHICLE_OWNER` FOREIGN KEY (`owner_id`)
    REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `vehicle` (`owner_id`, `brand`,   `model`,     `plate_number`, `is_electric`)
VALUES
  (
    (SELECT id FROM `user` WHERE email = 'driver1@example.com'),
    'Toyota',  'Corolla',   'ABC-123-EF',        0
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver2@example.com'),
    'Peugeot', '208',       'GH-456-IJ',         0
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver3@example.com'),
    'Tesla',   'Model 3',   'EV-789-KL',         1
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver4@example.com'),
    'Renault', 'Clio',      'MN-012-OP',         0
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver5@example.com'),
    'Volkswagen', 'ID 3',   'VW-333-ID3',        1
  );
