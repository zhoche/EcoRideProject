CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(180) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  pseudo VARCHAR(50) NOT NULL,
  roles JSONB NOT NULL,
  credits INT NOT NULL DEFAULT 0,
  gender CHAR(1) NOT NULL CHECK (gender IN ('M','F')),
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  image VARCHAR(255),
  rating DECIMAL(2,1),
  is_verified BOOLEAN NOT NULL DEFAULT FALSE,
  driver_preferences JSONB
);

INSERT INTO users (
    email,
    password,
    pseudo,
    roles,
    credits,
    gender,
    created_at,
    image,
    rating,
    is_verified,
    driver_preferences
) VALUES
  (
    'employee2@example.com',
    '$2y$13$y36z35vPmQT2Uxu6aQvbpeQfeUGUrpJ/q8SQEY5d2/iaqS2PjLvcG',
    'employee2',
    '["ROLE_EMPLOYEE"]',
    20,
    'M',
    NOW(),
    '/images/Profil_Employe.png',
    NULL,
    TRUE,
    NULL
  ),

  (
    'driver1@example.com',
    '$2y$13$jNKuiFJz4sItfUkvJ.Zfhub4FdWeKg1hzNdDoje9qzKNxLDcFEKk2',
    'jerome',
    '["ROLE_DRIVER","ROLE_USER"]',
    20,
    'M',
    NOW(),
    '/images/Profil_Jerome.png',
    4.5,
    TRUE,
    '{"Véhicule non-fumeur": true, "Femmes uniquement": false, "Animal de compagnie autorisé": true}'
  ),
  (
    'driver2@example.com',
    '$2y$13$RdSzWkV2LVN/IpZA1B92/.NEarbG9e1Oo4wEDbtxEKmpbL2OfyG82',
    'rosalie',
    '["ROLE_DRIVER","ROLE_USER"]',
    20,
    'F',
    NOW(),
    '/images/Profil_Passager-Conducteur.png',
    4,
    FALSE,
    '{"Véhicule non-fumeur": false, "Femmes uniquement": true, "Animal de compagnie autorisé": false}'
  ),
  (
    'driver3@example.com',
    '$2y$13$vAZW9Kr/X00LTyFTKjIAPetnDgti5nz4J3S7.dT6LJ9Ki67hV2m9K',
    'francky',
    '["ROLE_DRIVER","ROLE_USER"]',
    20,
    'M',
    NOW(),
    '/images/Profil_Francky.png',
    4,
    TRUE,
    '{"Véhicule non-fumeur": true, "Femmes uniquement": false, "Animal de compagnie autorisé": false}'
  ),
  (
    'driver4@example.com',
    '$2y$13$tmcejdGD4uImAq7IiWTuIOhceGAXETblfnUGFsFI7LZoDRm97iqzC',
    'kati',
    '["ROLE_DRIVER","ROLE_USER"]',
    20,
    'F',
    NOW(),
    '/images/Profil_Kati.png',
    5,
    TRUE,
    '{"Véhicule non-fumeur": false, "Femmes uniquement": false, "Animal de compagnie autorisé": true}'
  ),
  (
    'driver5@example.com',
    '$2y$13$CKqlzEXakkrom9jHFEW5pOjGSCuYFyTVp0P1aIOxAXVccbcF7H/ay',
    'anthony',
    '["ROLE_DRIVER","ROLE_USER"]',
    20,
    'F',
    NOW(),
    '/images/Profil_Anthony.png',
    5,
    TRUE,
    '{"Véhicule non-fumeur": false, "Femmes uniquement": false, "Animal de compagnie autorisé": true}'
  ),
  (
    'passenger@example.com',
    '$2y$13$OYTBYdtYtlumEW3sDE2nke0o5DFNYNQ3Buc17g0PYweT37b2YC1YW',
    'kati',
    '["ROLE_USER"]',
    20,
    'F',
    NOW(),
    '/images/Profil_Kati.png',
    5,
    TRUE,
    NULL
  ),
  (
    'passenger2@example.com',
    '$2y$13$G2SrJuPT7GUJAqYZeBKzKOOmCDvjnXS0jvzGj6M0UoSaCzwDCN86u',
    'alicia',
    '["ROLE_USER"]',
    20,
    'F',
    NOW(),
    '/images/Profil_Alicia.png',
    5,
    TRUE,
    NULL
  );
