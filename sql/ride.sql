CREATE TABLE `ride` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `driver_id` INT UNSIGNED NOT NULL,
  `departure` VARCHAR(255) NOT NULL,
  `arrival` VARCHAR(255) NOT NULL,
  `date` DATETIME NOT NULL,
  `available_seats` INT NOT NULL,
  `price` DECIMAL(5,2) NOT NULL,
  `vehicle_id` INT UNSIGNED NOT NULL,
  `initial_seats` INT NOT NULL,
  `extras` VARCHAR(255) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'en cours',
  PRIMARY KEY (`id`),
  KEY `IDX_RIDE_DRIVER` (`driver_id`),
  KEY `IDX_RIDE_VEHICLE` (`vehicle_id`),
  CONSTRAINT `FK_RIDE_DRIVER` FOREIGN KEY (`driver_id`)
    REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_RIDE_VEHICLE` FOREIGN KEY (`vehicle_id`)
    REFERENCES `vehicle` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `ride`
  (`driver_id`,                      `departure`,               `arrival`,                `date`,
   `available_seats`,                `price`,                   `vehicle_id`,             `initial_seats`,
   `extras`,                         `status`)
VALUES
  (
    (SELECT id FROM `user` WHERE email = 'driver1@example.com'),
    'Paris',                         'Lille',
    DATE_ADD(NOW(), INTERVAL 1 DAY),
    3,                                5.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'ABC-123-EF'),
    5,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver2@example.com'),
    'Lyon',                          'Grenoble',
    DATE_ADD(NOW(), INTERVAL 2 DAY),
    4,                                4.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'GH-456-IJ'),
    4,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver1@example.com'),
    'Bordeaux',                      'Toulouse',
    DATE_ADD(NOW(), INTERVAL 3 DAY),
    2,                                6.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'EV-789-KL'),
    3,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver3@example.com'),
    'Nantes',                        'Rennes',
    DATE_ADD(NOW(), INTERVAL 4 DAY),
    3,                                3.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'MN-012-OP'),
    5,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver2@example.com'),
    'Nice',                          'Marseille',
    DATE_ADD(NOW(), INTERVAL 5 DAY),
    5,                                3.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'VW-333-ID3'),
    5,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver1@example.com'),
    'Amiens',                        'Rouen',
    DATE_ADD(NOW(), INTERVAL 6 DAY),
    1,                                5.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'ABC-123-EF'),
    4,
    'Trajet convivial avec pauses',   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver3@example.com'),
    'Dijon',                         'Strasbourg',
    DATE_ADD(NOW(), INTERVAL 7 DAY),
    2,                                4.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'MN-012-OP'),
    5,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver1@example.com'),
    'Tours',                         'Orléans',
    DATE_ADD(NOW(), INTERVAL 8 DAY),
    4,                                3.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'EV-789-KL'),
    5,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver2@example.com'),
    'Avignon',                       'Montpellier',
    DATE_ADD(NOW(), INTERVAL 9 DAY),
    3,                                6.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'VW-333-ID3'),
    4,
    null,   'en cours'
  ),
  (
    (SELECT id FROM `user` WHERE email = 'driver3@example.com'),
    'Clermont-Ferrand',              'Saint-Étienne',
    DATE_ADD(NOW(), INTERVAL 10 DAY),
    2,                                5.00,
    (SELECT id FROM `vehicle` WHERE plate_number = 'MN-012-OP'),
    5,
    null,   'en cours'
  );
`
