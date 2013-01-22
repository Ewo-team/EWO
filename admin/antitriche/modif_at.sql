-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 31 Juillet 2011 à 17:54
-- Version du serveur: 5.1.53
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ewo`
--


DROP TABLE  `at_droits` ,
`at_inter` ,
`at_ip` ,
`at_ip_utilisateur` ,
`at_log` ,
`at_navigateur` ,
`at_navigateur_utilisateur` ,
`logs_at`,
`logs`;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `at_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `compte` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table générique de log' AUTO_INCREMENT=5 ;

--
-- Structure de la table `at_log_at`
--

CREATE TABLE IF NOT EXISTS `at_log_at` (
  `id` bigint(20) unsigned NOT NULL,
  `action` int(11) NOT NULL,
  `message` char(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_log_connexion`
--

CREATE TABLE IF NOT EXISTS `at_log_connexion` (
  `id` bigint(20) unsigned NOT NULL,
  `IP` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `cookieId` int(10) unsigned NOT NULL,
  `navigateur` int(10) unsigned NOT NULL,
  KEY `id` (`id`),
  KEY `navigateur` (`navigateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_members`
--

CREATE TABLE IF NOT EXISTS `at_members` (
  `id` int(10) unsigned NOT NULL,
  `lvl` int(10) unsigned NOT NULL,
  UNIQUE KEY `couple` (`id`,`lvl`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_navigateur`
--

CREATE TABLE IF NOT EXISTS `at_navigateur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descr` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `at_punish`
--

CREATE TABLE IF NOT EXISTS `at_punish` (
  `id` int(10) unsigned NOT NULL,
  `lvl` tinyint(3) unsigned NOT NULL,
  `at` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`,`lvl`),
  UNIQUE KEY `trio` (`id`,`lvl`,`at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `at_log_at`
--
ALTER TABLE `at_log_at`
  ADD CONSTRAINT `at_log_at_ibfk_1` FOREIGN KEY (`id`) REFERENCES `at_log` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `at_log_connexion`
--
ALTER TABLE `at_log_connexion`
  ADD CONSTRAINT `at_log_connexion_ibfk_1` FOREIGN KEY (`navigateur`) REFERENCES `at_navigateur` (`id`) ON DELETE CASCADE;
  
 ALTER TABLE `at_log_connexion`
  ADD CONSTRAINT `at_log_connexion_ibfk_2` FOREIGN KEY (`id`) REFERENCES `at_log` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `at_members`
--
ALTER TABLE `at_members`
  ADD CONSTRAINT `at_members_ibfk_1` FOREIGN KEY (`id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
  
  
  
/*
Modifs du 06/08/2011
*/

CREATE TABLE  `ewo`.`at_log_inter_geo` (
	`id` BIGINT UNSIGNED NOT NULL ,
	`id_perso1` INT UNSIGNED NOT NULL ,
	`x1` INT NOT NULL ,
	`y1` INT NOT NULL ,
	`id_perso2` INT UNSIGNED NOT NULL ,
	`x2` INT NOT NULL ,
	`y2` INT NOT NULL ,
	`carte_id` TINYINT UNSIGNED NOT NULL,
	`ref` BIGINT UNSIGNED NULL ,
	`lvl` TINYINT UNSIGNED NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE  `ewo`.`at_log_inter_geo` ADD PRIMARY KEY (  `id` );
ALTER TABLE  `ewo`.`at_log_inter_geo` ADD INDEX  `perso1` (  `id_perso1` );
ALTER TABLE  `ewo`.`at_log_inter_geo` ADD INDEX  `perso2` (  `id_perso2` );
ALTER TABLE  `ewo`.`at_log_inter_geo` ADD INDEX  `ref` (  `ref` );
ALTER TABLE  `ewo`.`at_log_inter_geo` ADD INDEX  `carte_id` (  `carte_id` );

ALTER TABLE  `at_log_inter_geo` ADD FOREIGN KEY (  `id` ) REFERENCES  `ewo`.`at_log` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE  `at_log_inter_geo` ADD FOREIGN KEY (  `id_perso1` ) REFERENCES  `ewo`.`persos` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE  `at_log_inter_geo` ADD FOREIGN KEY (  `id_perso2` ) REFERENCES  `ewo`.`persos` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE  `at_log_inter_geo` ADD FOREIGN KEY (  `ref` ) REFERENCES  `ewo`.`at_log_inter_geo` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE  `at_log_inter_geo` ADD FOREIGN KEY (  `carte_id` ) REFERENCES  `ewo`.`cartes` (
`id`
);
