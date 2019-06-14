-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           8.0.14 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

-- Export de la structure de la base pour snows
DROP DATABASE IF EXISTS `snows`;
CREATE DATABASE `snows`;
USE `snows`;

-- Export de la structure de la table snows. snows
CREATE TABLE IF NOT EXISTS `snows` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `brand` varchar(20) NOT NULL,
  `model` varchar(30) NOT NULL,
  `snowLength` int(4) unsigned NOT NULL,
  `qtyAvailable` smallint(6) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '0',
  `dailyPrice` float unsigned NOT NULL,
  `photo` varchar(50) DEFAULT NULL,
  `active` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `snow_code` (`code`)
);

-- Export de données de la table snows.snows : ~9 rows (environ)

INSERT INTO `snows` (`id`, `code`, `brand`, `model`, `snowLength`, `qtyAvailable`, `description`, `dailyPrice`, `photo`, `active`) VALUES
	(1, 'B101', 'Burton', 'Custom', 160, 22, 'La board la plus fiable de tous les temps, la solution snowboard pour tous les terrains. (Homme)', 29, 'view/content/images/B101_small.jpg', 1),
	(2, 'B126', 'Burton', 'Free Thinker', 165, 2, 'Élargissez votre vision grâce son interprétation du ride tout terrain dynamique sur la poudreuse. (Homme)', 45, 'view/content/images/B126_small.jpg', 1),
	(3, 'B327', 'Burton', 'Day Trader', 155, 6, 'Flottabilité sans effort et un contrôle qui renforce la confiance en soi. (Femme)', 25, 'view/content/images/B327_small.jpg', 0),
	(4, 'K266', 'K2', 'Wildheart', 152, 2, 'Keeping in versatile style (Femme)', 29, 'view/content/images/K266_small.jpg', 1),
	(5, 'N100', 'Nidecker', 'Tracer', 164, 11, 'Une expérience de carve hors du commun. Idéal pour carver comme jamais (Homme et femme)', 39, 'view/content/images/N100_small.jpg', 1),
	(6, 'N754', 'Nidecker', 'Ultralight', 166, 26, 'A la pointe de la technologie. Idéal pour le freeride sur les faces engagées (Homme et femme)', 59, 'view/content/images/N754_small.jpg', 1),
	(7, 'P067', 'Prior', 'Brandwine 153', 154, 9, 'High performance, directional Freeride board, draws a smooth, stable and fast line through all snow conditions. (Femme)', 49, 'view/content/images/P067_small.jpg', 1),
	(8, 'P165', 'Prior', 'BC Split 161', 169, 1, 'Sa forme directionnelle Freeride offre une ride plutôt douce et stable dans une variété de conditions', 35, 'view/content/images/P165_small.jpg', 1),
	(9, 'K409', 'K2', 'Lime Lite', 149, 15, 'Best For Freestyle Evolution with a Focus on Fun (Femme)', 55, 'view/content/images/K409_small.jpg', 1);

-- Export de la structure de la table snows. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userEmailAddress` varchar(255) NOT NULL,
  `userHashPsw` varchar(255) NOT NULL,
  `userType` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userEmailAddress` (`userEmailAddress`)
);

-- Export de la structure de la table snows. leasings
CREATE TABLE IF NOT EXISTS `leasings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUsers` int(10) UNSIGNED NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `statut` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Export de la structure de la table snows. snows_leasings
CREATE TABLE IF NOT EXISTS `snows_leasings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idLeasings` int(10) UNSIGNED NOT NULL,
  `idSnows` int(10) UNSIGNED NOT NULL,
  `qtySelected` int(10) UNSIGNED NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `statut` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Add foreign keys in table
Alter TABLE `leasings`
Add foreign key (`idUsers`) REFERENCES `users`(`id`);

Alter TABLE `snows_leasings`
Add foreign key (`idSnows`) REFERENCES `snows`(`id`);

Alter TABLE `snows_leasings`
Add foreign key (`idLeasings`) REFERENCES `leasings`(`id`);

SET GLOBAL FOREIGN_KEY_CHECKS=0;