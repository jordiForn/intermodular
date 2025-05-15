-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create clients table
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `cognom` varchar(100) DEFAULT NULL,
  `tlf` varchar(20) DEFAULT NULL,
  `direccio` varchar(255) DEFAULT NULL,
  `ciutat` varchar(100) DEFAULT NULL,
  `codi_postal` varchar(10) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `consulta` text DEFAULT NULL,
  `missatge` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update comandes table to reference clients table
ALTER TABLE `comandes` 
  ADD CONSTRAINT `comandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

-- Migration script to move data from client table to new structure
-- This should be run as a separate script after creating the new tables
/*
INSERT INTO users (username, email, password, role, created_at, updated_at)
SELECT nom_login, email, contrasena, rol, NOW(), NOW()
FROM client;

INSERT INTO clients (user_id, nom, cognom, tlf, consulta, missatge, created_at, updated_at)
SELECT 
    (SELECT id FROM users WHERE username = client.nom_login),
    nom,
    cognom,
    tlf,
    consulta,
    missatge,
    NOW(),
    NOW()
FROM client;

-- Update comandes to reference new client IDs
UPDATE comandes c
JOIN client old ON c.client_id = old.id
JOIN clients new ON new.user_id = (SELECT id FROM users WHERE username = old.nom_login)
SET c.client_id = new.id;
*/
