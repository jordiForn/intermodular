-- SQL para crear la tabla de servicios
CREATE TABLE IF NOT EXISTS `serveis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `descripcio` text NOT NULL,
  `preu` decimal(10,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imatge` varchar(255) DEFAULT NULL,
  `detalls` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de ejemplo para servicios de jardinería
INSERT INTO `serveis` (`nom`, `descripcio`, `preu`, `categoria`, `imatge`, `detalls`) VALUES
('Disseny de jardins', 'Servei professional de disseny de jardins adaptat a les teves necessitats i espai disponible.', 250.00, 'jardins', 'disseny-jardi.jpg', 'Inclou consulta inicial, esbós del disseny, selecció de plantes i pressupost detallat.'),
('Manteniment mensual', 'Servei de manteniment mensual per mantenir el teu jardí en perfectes condicions durant tot l\'any.', 120.00, 'jardins', 'manteniment-jardi.jpg', 'Inclou poda, reg, fertilització, control de plagues i neteja general.'),
('Instal·lació de reg automàtic', 'Disseny i instal·lació de sistemes de reg automàtic per optimitzar el consum d\'aigua.', 350.00, 'jardins', 'reg-automatic.jpg', 'Sistema programable amb sensors de pluja i humitat per estalviar aigua.'),
('Poda d\'arbres', 'Servei professional de poda d\'arbres per millorar la seva salut i aparença.', 80.00, 'jardins', 'poda-arbres.jpg', 'Inclou recollida i gestió dels residus vegetals.'),
('Tractament de plagues', 'Diagnòstic i tractament de plagues i malalties en plantes i arbres.', 90.00, 'jardins', 'tractament-plagues.jpg', 'Utilitzem productes ecològics i respectuosos amb el medi ambient.');

-- Datos de ejemplo para servicios de piscinas
INSERT INTO `serveis` (`nom`, `descripcio`, `preu`, `categoria`, `imatge`, `detalls`) VALUES
('Manteniment de piscines', 'Servei complet de manteniment de piscines per garantir aigua neta i segura.', 150.00, 'piscines', 'manteniment-piscina.jpg', 'Inclou anàlisi de l\'aigua, neteja de filtres, ajust de productes químics i neteja de superfícies.'),
('Reparació de piscines', 'Servei de reparació de piscines per solucionar fuites, esquerdes i altres problemes.', 200.00, 'piscines', 'reparacio-piscina.jpg', 'Diagnòstic complet i pressupost sense compromís.'),
('Instal·lació de piscines', 'Disseny i instal·lació de piscines a mida segons les teves necessitats.', 5000.00, 'piscines', 'installacio-piscina.jpg', 'Inclou excavació, construcció, instal·lació de sistemes de filtració i acabats.'),
('Neteja de piscines', 'Servei puntual de neteja profunda de piscines.', 100.00, 'piscines', 'neteja-piscina.jpg', 'Ideal per preparar la piscina per a la temporada d\'estiu o després d\'un període sense ús.'),
('Hivern de piscines', 'Preparació de la piscina per a la temporada d\'hivern.', 120.00, 'piscines', 'hivern-piscina.jpg', 'Inclou ajust de productes químics, col·locació de cobertes i protecció dels equips.');
