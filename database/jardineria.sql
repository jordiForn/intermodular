-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 17:27:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jardineria`
--
CREATE database IF NOT EXISTS `jardineria1` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `jardineria1`;

DELIMITER $$
--
-- Procedimientos
--
CREATE PROCEDURE `actualitzar_estoc` (IN `a_id` INT, IN `a_estoc` INT)   BEGIN
	UPDATE productes SET estoc = a_estoc WHERE id = a_id;
END$$

CREATE PROCEDURE `afegir_producte` (IN `p_nom` VARCHAR(100), IN `p_descripcio` VARCHAR(100), IN `p_preu` DOUBLE, IN `p_estoc` INT)   BEGIN
	INSERT INTO productes(nom, descripcio, preu, estoc)
    VALUES(p_nom, p_descripcio, p_preu, p_estoc);
END$$

CREATE PROCEDURE `borrar_productes_sense_estoc` ()   BEGIN
	DELETE FROM productes WHERE estoc = 0;
END$$

CREATE PROCEDURE `comandes_client` (IN `c_id` INT)   BEGIN
	SELECT * FROM comandes WHERE client_id = c_id;
END$$

CREATE PROCEDURE `productes_baix_estoc` ()   BEGIN
	SELECT nom, estoc FROM productes
    WHERE estoc < 5;
END$$

CREATE PROCEDURE `registrar_client` (IN `r_nom` VARCHAR(100), IN `r_email` CHAR(100), IN `r_tlf` INT, IN `r_nom_login` VARCHAR(100), IN `r_contrasena` INT)   BEGIN
	INSERT INTO client(nom, email, tlf, nom_login, contrasena) 
    VALUES(r_nom, r_email, r_tlf, r_nom_login, r_contrasena);
END$$

--
-- Funciones
--
CREATE  FUNCTION `calcular_total_comandes` () RETURNS DOUBLE DETERMINISTIC BEGIN
	DECLARE t_total double;
    
    SELECT sum(total)INTO t_total FROM comandes;
    
    RETURN t_total;
END$$

CREATE  FUNCTION `client_existeix` (`c_nom_login` VARCHAR(100)) RETURNS TINYINT(1) DETERMINISTIC BEGIN
	DECLARE result boolean;
    
    IF EXISTS (SELECT 1 FROM client WHERE nom_login = c_nom_login) THEN
        SET result = TRUE;
    ELSE
    	SET result = false;
    END IF;
	RETURN result;
END$$

CREATE FUNCTION `comanda_mes_cara` () RETURNS INT(11) DETERMINISTIC BEGIN
	DECLARE t_total double;
    
    SELECT MAX(total) INTO t_total FROM comandes LIMIT 1;
    
    RETURN t_total;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `id_referit` int(11) DEFAULT NULL,
  `id_referidor` int(11) DEFAULT NULL,
  `id_fidelitat` int(11) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `cognom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tlf` varchar(15) DEFAULT NULL,
  `consulta` text DEFAULT NULL,
  `missatge` text DEFAULT NULL,
  `nom_login` varchar(50) DEFAULT NULL,
  `contrasena` varchar(20) DEFAULT NULL,
  `rol` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `client`
--

INSERT INTO `client` (`id`, `id_referit`, `id_referidor`, `id_fidelitat`, `nom`, `cognom`, `email`, `tlf`, `consulta`, `missatge`, `nom_login`, `contrasena`, `rol`) VALUES
(1, NULL, NULL, NULL, 'Jordi', 'Fornes', 'jordi@gmail.com', '123456789', NULL, '| Hola', 'Jordi', '123', 1),
(4, NULL, NULL, NULL, 'Jordi2', '', 'a@a.com', '123456780', '| Hola| Hola', NULL, 'Jordi2', '123', 0),
(6, NULL, NULL, NULL, 'Jordi3', '', 'a@a.com', '111111111', NULL, NULL, 'Jordi3', '123', 0),
(8, NULL, NULL, NULL, 'exemple', NULL, 'exemple@exemple.com', '123456788', NULL, NULL, 'exemple', '111', 0);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `client_dades`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `client_dades` (
`nom` varchar(100)
,`cognom` varchar(100)
,`email` varchar(100)
,`tlf` varchar(15)
,`nom_login` varchar(50)
,`rol` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `client_id`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `client_id` (
`id` int(11)
,`nom` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandes`
--

CREATE TABLE `comandes` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `data_comanda` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL CHECK (`total` >= 0),
  `estat` enum('Pendent','Enviat','Completat','Cancel·lat') DEFAULT 'Pendent',
  `direccio_enviament` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comandes`
--

INSERT INTO `comandes` (`id`, `client_id`, `data_comanda`, `total`, `estat`, `direccio_enviament`) VALUES
(1, 1, '2025-02-15 11:50:58', 26.49, 'Pendent', 'HOLAAA'),
(2, 1, '2025-02-15 11:51:19', 26.49, 'Pendent', 'HOLAAA'),
(3, 1, '2025-02-15 11:51:41', 26.49, 'Pendent', 'HOLAAA'),
(4, 1, '2025-02-15 11:52:37', 26.49, 'Pendent', 'HOLAAA'),
(5, 1, '2025-02-15 11:52:56', 26.49, 'Pendent', 'HOLAAA'),
(10, 1, '2025-03-13 16:02:44', 11.49, 'Pendent', 'HOLAAA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrata`
--

CREATE TABLE `contrata` (
  `id` int(11) NOT NULL,
  `id_servei` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `data_contracte` date DEFAULT NULL,
  `estat_contracte` varchar(50) DEFAULT NULL,
  `preu_final` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleats`
--

CREATE TABLE `empleats` (
  `id` int(11) NOT NULL,
  `id_equip` int(11) DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `cognom` varchar(100) DEFAULT NULL,
  `edat` int(11) DEFAULT NULL,
  `ss` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `c_banc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equip`
--

CREATE TABLE `equip` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipament`
--

CREATE TABLE `equipament` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `disponibilitat` varchar(50) DEFAULT NULL,
  `estat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipament_programat`
--

CREATE TABLE `equipament_programat` (
  `id_equipament` int(11) NOT NULL,
  `id_programacio` int(11) NOT NULL,
  `quantitat_utilitzada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fidelitat`
--

CREATE TABLE `fidelitat` (
  `id` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `punts_acumulats` int(11) DEFAULT NULL,
  `punts_gastats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `quantitat_disponible` int(11) DEFAULT NULL,
  `caducitat` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_programat`
--

CREATE TABLE `material_programat` (
  `id_material` int(11) NOT NULL,
  `id_programacio` int(11) NOT NULL,
  `quantitat_utilitzada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagament`
--

CREATE TABLE `pagament` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productes`
--

CREATE TABLE `productes` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `descripcio` text NOT NULL,
  `preu` decimal(10,2) NOT NULL CHECK (`preu` >= 0),
  `estoc` int(11) NOT NULL CHECK (`estoc` >= 0),
  `categoria` enum('Plantes i llavors','Terra i adobs','Ferramentes','Prueba 1') DEFAULT NULL,
  `imatge` varchar(255) DEFAULT NULL,
  `detalls` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productes`
--

INSERT INTO `productes` (`id`, `nom`, `descripcio`, `preu`, `estoc`, `categoria`, `imatge`, `detalls`) VALUES
(11, 'Adob Ecològic', 'Adob natural per a un millor creixement de les plantes.', 8.75, 25, 'Terra i adobs', 'adob_eco.jpg', 'Aquest adob està elaborat amb ingredients 100% naturals, garantint una fertilització sostenible i equilibrada per al sòl. Ideal per a hort ecològic i cultius orgànics, millora la retenció d’aigua i pr'),
(12, 'Adob per a Floració', 'Adob especial per potenciar la floració de les plantes.', 9.50, 20, 'Terra i adobs', 'adob_floracio.jpg', 'Fertilitzant especial formulat per estimular la floració de plantes ornamentals i hortícoles. Ric en fòsfor i potassi, dos elements essencials per a la producció de flors més abundants, amb colors viu'),
(13, 'Alfabrega', 'Planta aromàtica ideal per a cuina i infusions.', 3.99, 30, 'Plantes i llavors', 'alfabrega.jpg', 'Planta aromàtica de ràpid creixement, molt utilitzada en la cuina mediterrània. Les seves fulles fresques donen un toc especial a amanides, salses, pizzes i infusions. A més, té propietats digestives '),
(14, 'Begònia', 'Planta ornamental amb flors vistoses de colors vius.', 7.50, 15, 'Plantes i llavors', 'begonia.jpg', 'Planta ornamental amb flors vistoses que poden ser de diferents colors: vermell, rosa, taronja, groc o blanc. És ideal per decorar interiors i exteriors, resistint bé en zones ombrívoles. Necessita un'),
(15, 'Compost Ecològic', 'Compost natural per millorar la qualitat del sòl.', 12.00, 18, 'Terra i adobs', 'compost_eco.jpg', 'Compost orgànic d’alta qualitat, fet a partir de matèria orgànica descomposta, que millora l’estructura del sòl i incrementa la seva fertilitat de manera natural. Afavoreix la retenció d’aigua i airea'),
(16, 'Encisam', 'Llavors per cultivar encisam fresc i saludable.', 2.99, 50, 'Plantes i llavors', 'encisam.jpg', 'Llavors d’enciam d’alta qualitat per a la producció de fulles tendres i saboroses. Ideal per al cultiu en hort urbà o testos grans. Es recomana sembrar en zones assolellades i mantenir un reg constant'),
(17, 'Gerani', 'Planta ornamental amb flors de colors vius, ideal per a balcons.', 6.75, 20, 'Plantes i llavors', 'gerani.jpg', 'Planta ornamental molt resistent, coneguda per les seves flors brillants i fragants. Aporta un toc de color a balcons, terrasses i jardins. Es cultiva fàcilment i floreix durant la primavera i l’estiu'),
(18, 'Petúnia', 'Planta amb flors resistents i colors vibrants.', 5.99, 22, 'Plantes i llavors', 'petunia.jpg', 'Planta anual amb una gran varietat de colors vius. És perfecta per a parterres, testos i jardineres, ja que floreix abundantment durant la primavera i l’estiu. Necessita exposició al sol i regs modera'),
(19, 'Romaní', 'Planta aromàtica amb múltiples usos en cuina i medicina.', 4.50, 40, 'Plantes i llavors', 'romani.jpg', 'Arbust aromàtic perenne amb múltiples usos culinaris i medicinals. És molt resistent a la sequera i pot créixer en sòls pobres. Les seves fulles s’utilitzen per donar aroma a plats de carn, guisats i '),
(20, 'Sàlvia', 'Planta medicinal i aromàtica, ideal per a infusions.', 4.75, 35, 'Plantes i llavors', 'salvia.jpg', 'Planta medicinal i aromàtica amb una llarga tradició d’ús en la medicina natural. Les seves fulles tenen propietats antiinflamatòries i ajuden a millorar la digestió. A més, s’utilitza en infusions i '),
(21, 'Terra Universal 50L', 'Terra de qualitat per a tot tipus de plantes.', 12.00, 30, 'Terra i adobs', 'terra_universal.jpg', 'Substrat de qualitat premium per a tot tipus de plantes, especialment formulat per proporcionar una barreja equilibrada de nutrients essencials. Millora la retenció d’aigua i l’aireació del sòl, fomen'),
(22, 'Terra per Testos', 'Sòl especial per a plantes en test.', 10.50, 28, 'Terra i adobs', 'terra.jpg', 'Sòl especial per a plantes en testos, amb una estructura lleugera i porosa que millora el drenatge i evita l’excés d’humitat. Conté matèria orgànica que proporciona els nutrients necessaris per al des'),
(23, 'Timó', 'Planta aromàtica resistent, ideal per a jardins i cuina.', 3.99, 25, 'Plantes i llavors', 'timó.jpg', 'Planta aromàtica de baix manteniment, resistent a la sequera i molt apreciada per la seva aroma i propietats medicinals. Es pot utilitzar en infusions per combatre refredats o com a condiment per a pl'),
(24, 'Tomaca', 'Llavors per cultivar tomàquets ecològics.', 3.50, 50, 'Plantes i llavors', 'tomaca.jpg', 'Llavors de tomàquet ecològic, seleccionades per garantir una alta producció i un sabor intens. Es poden cultivar tant en horts tradicionals com en testos grans. Els fruits són sucosos i perfectes per '),
(34, 'Prueba', 'Prueba', 1.00, 1, 'Prueba 1', NULL, NULL),
(35, 'exemple', 'exemple1', 10.00, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacio`
--

CREATE TABLE `programacio` (
  `id` int(11) NOT NULL,
  `id_equip` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `estat_servei` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programar`
--

CREATE TABLE `programar` (
  `id_servei` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_programacio` int(11) NOT NULL,
  `tipus_servei` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocio`
--

CREATE TABLE `promocio` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `descripcio` text DEFAULT NULL,
  `punts_req` int(11) DEFAULT NULL,
  `data_inici` date DEFAULT NULL,
  `data_fi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsabilitzar`
--

CREATE TABLE `responsabilitzar` (
  `id_equip` int(11) NOT NULL,
  `id_empleat` int(11) NOT NULL,
  `num_persones_a_carrec` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ressenya`
--

CREATE TABLE `ressenya` (
  `id` int(11) NOT NULL,
  `comentari` text DEFAULT NULL,
  `valoracio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ressenyar`
--

CREATE TABLE `ressenyar` (
  `id_client` int(11) NOT NULL,
  `id_ressenya` int(11) NOT NULL,
  `id_servei` int(11) DEFAULT NULL,
  `id_programacio` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servei`
--

CREATE TABLE `servei` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `cat` varchar(50) DEFAULT NULL,
  `preu_base` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servei_jardins`
--

CREATE TABLE `servei_jardins` (
  `id_servei` int(11) NOT NULL,
  `cat` varchar(50) DEFAULT NULL,
  `descripcio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servei_piscines`
--

CREATE TABLE `servei_piscines` (
  `id_servei` int(11) NOT NULL,
  `cat` varchar(50) DEFAULT NULL,
  `descripcio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servei_realitzat`
--

CREATE TABLE `servei_realitzat` (
  `id_client` int(11) NOT NULL,
  `id_servei` int(11) NOT NULL,
  `id_programacio` int(11) NOT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `utilitza`
--

CREATE TABLE `utilitza` (
  `id_client` int(11) DEFAULT NULL,
  `id_promocio` int(11) NOT NULL,
  `id_fidelitat` int(11) NOT NULL,
  `punts_utilitzats` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura para la vista `client_dades`
--
DROP TABLE IF EXISTS `client_dades`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `client_dades`  AS SELECT `client`.`nom` AS `nom`, `client`.`cognom` AS `cognom`, `client`.`email` AS `email`, `client`.`tlf` AS `tlf`, `client`.`nom_login` AS `nom_login`, `client`.`rol` AS `rol` FROM `client` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `client_id`
--
DROP TABLE IF EXISTS `client_id`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `client_id`  AS SELECT `client`.`id` AS `id`, `client`.`nom` AS `nom` FROM `client` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comandes`
--
ALTER TABLE `comandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indices de la tabla `contrata`
--
ALTER TABLE `contrata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_servei` (`id_servei`,`id_client`),
  ADD UNIQUE KEY `id_client` (`id_client`,`id_servei`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `empleats`
--
ALTER TABLE `empleats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equip` (`id_equip`);

--
-- Indices de la tabla `equip`
--
ALTER TABLE `equip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipament`
--
ALTER TABLE `equipament`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipament_programat`
--
ALTER TABLE `equipament_programat`
  ADD PRIMARY KEY (`id_equipament`,`id_programacio`),
  ADD KEY `id_programacio` (`id_programacio`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fidelitat`
--
ALTER TABLE `fidelitat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `material_programat`
--
ALTER TABLE `material_programat`
  ADD PRIMARY KEY (`id_material`,`id_programacio`),
  ADD KEY `id_programacio` (`id_programacio`);

--
-- Indices de la tabla `pagament`
--
ALTER TABLE `pagament`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `productes`
--
ALTER TABLE `productes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `programacio`
--
ALTER TABLE `programacio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equip` (`id_equip`);

--
-- Indices de la tabla `programar`
--
ALTER TABLE `programar`
  ADD PRIMARY KEY (`id_servei`,`id_client`,`id_programacio`),
  ADD KEY `id_programacio` (`id_programacio`);

--
-- Indices de la tabla `promocio`
--
ALTER TABLE `promocio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `responsabilitzar`
--
ALTER TABLE `responsabilitzar`
  ADD PRIMARY KEY (`id_equip`,`id_empleat`),
  ADD KEY `id_empleat` (`id_empleat`);

--
-- Indices de la tabla `ressenya`
--
ALTER TABLE `ressenya`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ressenyar`
--
ALTER TABLE `ressenyar`
  ADD PRIMARY KEY (`id_client`,`id_ressenya`),
  ADD KEY `id_ressenya` (`id_ressenya`),
  ADD KEY `id_programacio` (`id_programacio`,`id_servei`);

--
-- Indices de la tabla `servei`
--
ALTER TABLE `servei`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servei_jardins`
--
ALTER TABLE `servei_jardins`
  ADD PRIMARY KEY (`id_servei`);

--
-- Indices de la tabla `servei_piscines`
--
ALTER TABLE `servei_piscines`
  ADD PRIMARY KEY (`id_servei`);

--
-- Indices de la tabla `servei_realitzat`
--
ALTER TABLE `servei_realitzat`
  ADD PRIMARY KEY (`id_client`,`id_servei`,`id_programacio`),
  ADD UNIQUE KEY `id_programacio` (`id_programacio`,`id_servei`);

--
-- Indices de la tabla `utilitza`
--
ALTER TABLE `utilitza`
  ADD PRIMARY KEY (`id_promocio`,`id_fidelitat`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_fidelitat` (`id_fidelitat`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `comandes`
--
ALTER TABLE `comandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productes`
--
ALTER TABLE `productes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comandes`
--
ALTER TABLE `comandes`
  ADD CONSTRAINT `comandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contrata`
--
ALTER TABLE `contrata`
  ADD CONSTRAINT `contrata_ibfk_1` FOREIGN KEY (`id_servei`) REFERENCES `servei` (`id`),
  ADD CONSTRAINT `contrata_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `contrata_ibfk_3` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id`);

--
-- Filtros para la tabla `empleats`
--
ALTER TABLE `empleats`
  ADD CONSTRAINT `empleats_ibfk_1` FOREIGN KEY (`id_equip`) REFERENCES `equip` (`id`);

--
-- Filtros para la tabla `equipament_programat`
--
ALTER TABLE `equipament_programat`
  ADD CONSTRAINT `equipament_programat_ibfk_1` FOREIGN KEY (`id_equipament`) REFERENCES `equipament` (`id`),
  ADD CONSTRAINT `equipament_programat_ibfk_2` FOREIGN KEY (`id_programacio`) REFERENCES `programacio` (`id`);

--
-- Filtros para la tabla `fidelitat`
--
ALTER TABLE `fidelitat`
  ADD CONSTRAINT `fidelitat_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`);

--
-- Filtros para la tabla `material_programat`
--
ALTER TABLE `material_programat`
  ADD CONSTRAINT `material_programat_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`),
  ADD CONSTRAINT `material_programat_ibfk_2` FOREIGN KEY (`id_programacio`) REFERENCES `programacio` (`id`);

--
-- Filtros para la tabla `pagament`
--
ALTER TABLE `pagament`
  ADD CONSTRAINT `pagament_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id`);

--
-- Filtros para la tabla `programacio`
--
ALTER TABLE `programacio`
  ADD CONSTRAINT `programacio_ibfk_1` FOREIGN KEY (`id_equip`) REFERENCES `equip` (`id`);

--
-- Filtros para la tabla `programar`
--
ALTER TABLE `programar`
  ADD CONSTRAINT `programar_ibfk_1` FOREIGN KEY (`id_programacio`) REFERENCES `programacio` (`id`),
  ADD CONSTRAINT `programar_ibfk_2` FOREIGN KEY (`id_servei`,`id_client`) REFERENCES `contrata` (`id_servei`, `id_client`);

--
-- Filtros para la tabla `responsabilitzar`
--
ALTER TABLE `responsabilitzar`
  ADD CONSTRAINT `responsabilitzar_ibfk_1` FOREIGN KEY (`id_equip`) REFERENCES `equip` (`id`),
  ADD CONSTRAINT `responsabilitzar_ibfk_2` FOREIGN KEY (`id_empleat`) REFERENCES `empleats` (`id`);

--
-- Filtros para la tabla `ressenyar`
--
ALTER TABLE `ressenyar`
  ADD CONSTRAINT `ressenyar_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `ressenyar_ibfk_2` FOREIGN KEY (`id_ressenya`) REFERENCES `ressenya` (`id`),
  ADD CONSTRAINT `ressenyar_ibfk_3` FOREIGN KEY (`id_programacio`,`id_servei`) REFERENCES `servei_realitzat` (`id_programacio`, `id_servei`);

--
-- Filtros para la tabla `servei_jardins`
--
ALTER TABLE `servei_jardins`
  ADD CONSTRAINT `servei_jardins_ibfk_1` FOREIGN KEY (`id_servei`) REFERENCES `servei` (`id`);

--
-- Filtros para la tabla `servei_piscines`
--
ALTER TABLE `servei_piscines`
  ADD CONSTRAINT `servei_piscines_ibfk_1` FOREIGN KEY (`id_servei`) REFERENCES `servei` (`id`);

--
-- Filtros para la tabla `servei_realitzat`
--
ALTER TABLE `servei_realitzat`
  ADD CONSTRAINT `servei_realitzat_ibfk_1` FOREIGN KEY (`id_client`,`id_servei`) REFERENCES `contrata` (`id_client`, `id_servei`),
  ADD CONSTRAINT `servei_realitzat_ibfk_2` FOREIGN KEY (`id_client`,`id_servei`,`id_programacio`) REFERENCES `programar` (`id_servei`, `id_client`, `id_programacio`);

--
-- Filtros para la tabla `utilitza`
--
ALTER TABLE `utilitza`
  ADD CONSTRAINT `utilitza_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `utilitza_ibfk_2` FOREIGN KEY (`id_promocio`) REFERENCES `promocio` (`id`),
  ADD CONSTRAINT `utilitza_ibfk_3` FOREIGN KEY (`id_fidelitat`) REFERENCES `fidelitat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
