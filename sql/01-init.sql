SET NAMES utf8;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `id` varchar(40) PRIMARY KEY NOT NULL,
    `email` VARCHAR(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
    `password` VARCHAR(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
    `est_superadmin` BOOLEAN DEFAULT FALSE,
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `libelle` VARCHAR(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL UNIQUE,
    `description_md` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `evenement`;
CREATE TABLE `evenement` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `titre` TEXT NOT NULL,
    `description_md` TEXT NOT NULL,
    `tarif` VARCHAR(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
    `date_debut` DATE NOT NULL,
    `date_fin` DATE,
    `horaire` TIME,
    `publie` BOOLEAN DEFAULT FALSE,
    `image` VARCHAR(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
    `categorie_id` int(11) NOT NULL REFERENCES categorie(id),
    `cree_par` varchar(40) NOT NULL REFERENCES user(id),
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;