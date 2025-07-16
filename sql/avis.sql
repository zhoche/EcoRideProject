CREATE TABLE `avis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ride_id` INT UNSIGNED NULL,
  `driver_id` INT UNSIGNED NULL,
  `passenger_id` INT UNSIGNED NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NULL,
  `status` VARCHAR(255) NULL,
  `token` VARCHAR(255) NULL,
  `is_validated` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `IDX_AVIS_RIDE` (`ride_id`),
  KEY `IDX_AVIS_DRIVER` (`driver_id`),
  KEY `IDX_AVIS_PASSENGER` (`passenger_id`),
  CONSTRAINT `FK_AVIS_RIDE` FOREIGN KEY (`ride_id`)
    REFERENCES `ride`   (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_AVIS_DRIVER` FOREIGN KEY (`driver_id`)
    REFERENCES `user`   (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_AVIS_PASSENGER` FOREIGN KEY (`passenger_id`)
    REFERENCES `user`   (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `avis`
  (`ride_id`,                                `driver_id`,                                      `passenger_id`,                                      `rating`, `comment`,               `status`,      `token`,                         `is_validated`)
VALUES
  (
    (SELECT r.id FROM `ride` AS r WHERE r.departure = 'Paris' AND r.arrival = 'Lille' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'driver1@example.com' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'passenger@example.com'   LIMIT 1),
    5,
    'Super trajet, merci !',
    'validé',
    '117e9f0dee2682b6',
    1
  ),
  (
    (SELECT r.id FROM `ride` AS r WHERE r.departure = 'Lyon'  AND r.arrival = 'Grenoble' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'driver2@example.com' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'passenger2@example.com'  LIMIT 1),
    4,
    'Conduite agréable.',
    'à traiter',
    '7d353584e102cde4',
    0
  ),
  (
    (SELECT r.id FROM `ride` AS r WHERE r.departure = 'Bordeaux' AND r.arrival = 'Toulouse' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'driver1@example.com' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'passenger@example.com'   LIMIT 1),
    3,
    'Un peu de retard, mais sympa.',
    'refusé',
    'bbc29648bbdebabc',
    0
  ),
  (
    (SELECT r.id FROM `ride` AS r WHERE r.departure = 'Nantes' AND r.arrival = 'Rennes' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'driver3@example.com' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'passenger2@example.com'  LIMIT 1),
    5,
    'Très bonne ambiance.',
    'validé',
    'bb26de9c2089698f',
    1
  ),
  (
    (SELECT r.id FROM `ride` AS r WHERE r.departure = 'Nice' AND r.arrival = 'Marseille' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'driver2@example.com' LIMIT 1),
    (SELECT u.id FROM `user` AS u WHERE u.email = 'passenger@example.com'   LIMIT 1),
    4,
    'Voiture propre et confortable.',
    'en attente',
    '2f0607cb1c14c490',
    0
  );
