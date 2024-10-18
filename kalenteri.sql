-- Tietokannan luominen
CREATE DATABASE IF NOT EXISTS kalenteri;
USE kalenteri;

-- Taulun `users` luominen
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kayttajatunnus` int(11) NOT NULL,
  `salasana` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `luotu` datetime NOT NULL,
  `etunimi` varchar(50) DEFAULT NULL,
  `sukunimi` varchar(50) DEFAULT NULL,
  `puhelinnumero` varchar(15) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `aktiivinen` tinyint(1) DEFAULT 1,
  `kuva` varchar(255) DEFAULT NULL,
  `paivitetty` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `roles` luominen
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NOT NULL,
  `value` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Vedos taulusta `roles`
INSERT INTO `roles` (`id`, `name`, `value`) VALUES
(1, 'user', 1),
(2, 'mainuser', 2),
(3, 'admin', 3)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `value` = VALUES(`value`);

-- Taulun `tapahtumat` luominen
CREATE TABLE IF NOT EXISTS `tapahtumat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nimi` varchar(100) NOT NULL,
  `tapahtumat` text NOT NULL,
  `paivamaara` date NOT NULL,
  `aika` time NOT NULL,
  `luotu` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `vapaat_ajat` luominen
CREATE TABLE IF NOT EXISTS `vapaat_ajat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paivamaara` date NOT NULL,
  `vapaat_ajat` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `varaukset` luominen
CREATE TABLE IF NOT EXISTS `varaukset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kayttajatunnus` int(11) NOT NULL,
  `paivamaara` date NOT NULL,
  `aika` time NOT NULL,
  `luotu` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`kayttajatunnus`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `rememberme_tokens` luominen
CREATE TABLE IF NOT EXISTS `rememberme_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `kayttajatunnus` int(11) NOT NULL,
  `expiry` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`kayttajatunnus`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Taulun `resetpassword_tokens` luominen
CREATE TABLE IF NOT EXISTS `resetpassword_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `kayttajatunnus` int(11) NOT NULL,
  `luotu` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`kayttajatunnus`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `signup_tokens` luominen
CREATE TABLE IF NOT EXISTS `signup_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `kayttajatunnus` int(11) NOT NULL,
  `luotu` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`kayttajatunnus`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Taulun `vieraskirja` luominen
CREATE TABLE IF NOT EXISTS `vieraskirja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nimi` varchar(100) NOT NULL,
  `viesti` text NOT NULL,
  `aika` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;