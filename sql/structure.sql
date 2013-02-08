-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 06 Février 2013 à 17:55
-- Version du serveur: 5.5.23-log
-- Version de PHP: 5.3.10

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données: `ewo`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `archivage_classements`$$
CREATE PROCEDURE `archivage_classements`()
BEGIN
	REPLACE INTO classement SELECT * FROM classement_view;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nom` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `cout` int(11) NOT NULL DEFAULT '1' COMMENT 'cout en PA de l''action',
  `cercle_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'cercle auquel est lié cette action defaut = 0',
  `niv` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'niveau de magie requis pour accéder à  cette action',
  `race` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'race pouvant realiser cette action. 1000 pour les humains, 0100 pour les parias, 1100 pour humain et paria etc ...',
  `grade` smallint(6) NOT NULL DEFAULT '-2',
  `galon` smallint(6) NOT NULL DEFAULT '0',
  `zone` mediumint(9) NOT NULL COMMENT 'taille de la zone d''effet autour de la cible. -1 si egale à la vision du lanceur.',
  `cible` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'Prise en compte de la cible ou non. (permet de déplacer une zone sur une cible autre que le lanceur)',
  `lanceur` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'Prise en compte du lanceur ou non.',
  `id_effet` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(lanceur_id_effet1),..,(lanceur_id_effetn):(cible_id_effet1),..,(cible_id_effetk)',
  `type_cible` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'allie, ennemi, both,choix, none',
  `type_action` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_Action_CercleId` (`cercle_id`),
  KEY `IX_Action_Niveau` (`niv`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=160 ;

-- --------------------------------------------------------

--
-- Structure de la table `api_key`
--

DROP TABLE IF EXISTS `api_key`;
CREATE TABLE IF NOT EXISTS `api_key` (
  `utilisateur_id` int(10) unsigned NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cle` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `niveau` enum('public','private','full') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`nom`),
  UNIQUE KEY `UU_ApiKey_Cle` (`cle`),
  KEY `FK_ApiKey_Utilisateurs` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_log`
--

DROP TABLE IF EXISTS `at_log`;
CREATE TABLE IF NOT EXISTS `at_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `compte` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table générique de log' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `at_log_at`
--

DROP TABLE IF EXISTS `at_log_at`;
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

DROP TABLE IF EXISTS `at_log_connexion`;
CREATE TABLE IF NOT EXISTS `at_log_connexion` (
  `id` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `cookieId` int(10) unsigned NOT NULL,
  `navigateur` int(10) unsigned NOT NULL,
  KEY `id` (`id`),
  KEY `navigateur` (`navigateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_log_inter_geo`
--

DROP TABLE IF EXISTS `at_log_inter_geo`;
CREATE TABLE IF NOT EXISTS `at_log_inter_geo` (
  `id` bigint(20) unsigned NOT NULL,
  `id_perso1` int(10) unsigned NOT NULL,
  `x1` int(11) NOT NULL,
  `y1` int(11) NOT NULL,
  `id_perso2` int(10) unsigned NOT NULL,
  `x2` int(11) NOT NULL,
  `y2` int(11) NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL,
  `ref` bigint(20) unsigned DEFAULT NULL,
  `lvl` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `perso1` (`id_perso1`),
  KEY `perso2` (`id_perso2`),
  KEY `ref` (`ref`),
  KEY `carte_id` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `at_members`
--

DROP TABLE IF EXISTS `at_members`;
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

DROP TABLE IF EXISTS `at_navigateur`;
CREATE TABLE IF NOT EXISTS `at_navigateur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descr` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `at_punish`
--

DROP TABLE IF EXISTS `at_punish`;
CREATE TABLE IF NOT EXISTS `at_punish` (
  `id` int(10) unsigned NOT NULL,
  `lvl` tinyint(3) unsigned NOT NULL,
  `at` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`,`lvl`),
  UNIQUE KEY `trio` (`id`,`lvl`,`at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `background`
--

DROP TABLE IF EXISTS `background`;
CREATE TABLE IF NOT EXISTS `background` (
  `perso_id` int(10) unsigned NOT NULL,
  `classe_rp` tinyint(4) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `historique` text COLLATE utf8_unicode_ci NOT NULL,
  `avis_ange` text COLLATE utf8_unicode_ci NOT NULL,
  `avis_demon` text COLLATE utf8_unicode_ci NOT NULL,
  `avis_humain` text COLLATE utf8_unicode_ci NOT NULL,
  `classe_visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `background_classes`
--

DROP TABLE IF EXISTS `background_classes`;
CREATE TABLE IF NOT EXISTS `background_classes` (
  `id` tinyint(3) NOT NULL,
  `camps` tinyint(3) NOT NULL,
  `groupe` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`camps`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bals`
--

DROP TABLE IF EXISTS `bals`;
CREATE TABLE IF NOT EXISTS `bals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `perso_src_id` int(10) unsigned DEFAULT NULL,
  `perso_dest_id` int(10) unsigned DEFAULT NULL,
  `corps_id` bigint(20) unsigned NOT NULL,
  `flag_lu` tinyint(1) NOT NULL DEFAULT '0',
  `flag_envoye` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `flag_archive` tinyint(1) NOT NULL,
  `flag_favori` tinyint(1) NOT NULL DEFAULT '0',
  `nom_liste` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Bals_Corps` (`corps_id`),
  KEY `FK_Bals_Expediteur` (`perso_src_id`),
  KEY `FK_Bals_Destinataire` (`perso_dest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Destinataires des BAL' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `bals_corps`
--

DROP TABLE IF EXISTS `bals_corps`;
CREATE TABLE IF NOT EXISTS `bals_corps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `corps` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `liste_mats` mediumtext CHARACTER SET latin1 NOT NULL,
  `liste` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_BalsCorps_Date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Corps des BAL' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `bals_listes`
--

DROP TABLE IF EXISTS `bals_listes`;
CREATE TABLE IF NOT EXISTS `bals_listes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ouverture` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = fermée / 1 = ouverte',
  `type` enum('public','prive','aura') COLLATE utf8_unicode_ci NOT NULL,
  `camp` tinyint(3) unsigned DEFAULT NULL COMMENT 'Camps correspondant à cette liste',
  `owner` int(10) unsigned DEFAULT NULL COMMENT 'propriétaire de la liste, lui seul peut la modifier',
  `liste` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_BalsListes_Camp` (`camp`),
  KEY `FK_BalsListes_Persos` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `bals_listeview`
--
DROP VIEW IF EXISTS `bals_listeview`;
CREATE TABLE IF NOT EXISTS `bals_listeview` (
`id` int(10) unsigned
,`libelle` varchar(50)
,`ouverture` tinyint(1)
,`type` enum('public','prive','aura')
,`camp` tinyint(3) unsigned
,`owner` int(10) unsigned
,`liste` mediumtext
,`grade` smallint(6)
,`pos_x` int(11)
,`pos_y` int(11)
,`carte` tinyint(3) unsigned
);
-- --------------------------------------------------------

--
-- Structure de la table `bals_send`
--

DROP TABLE IF EXISTS `bals_send`;
CREATE TABLE IF NOT EXISTS `bals_send` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `perso_src_id` int(10) unsigned DEFAULT '0' COMMENT 'perso source de la bal',
  `perso_dest_id` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'perso destinataire de la bal',
  `titre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `corps` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flag_lu` tinyint(1) NOT NULL,
  `flag_exp` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'qui envoie le mail, anim, admin, joueur',
  `flag_fav` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `liste_bal` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_BalsSend_Persos` (`perso_src_id`),
  KEY `IX_BalsSend_Date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
CREATE TABLE IF NOT EXISTS `blocks` (
  `unique_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perso_id` int(10) unsigned NOT NULL,
  `block_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `column_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` smallint(6) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'permet de rendre visible ou non un block',
  PRIMARY KEY (`unique_id`),
  UNIQUE KEY `UU_Blocks_PersoId_BlockId` (`perso_id`,`block_id`),
  KEY `FK_Block_Persos` (`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `camps`
--

DROP TABLE IF EXISTS `camps`;
CREATE TABLE IF NOT EXISTS `camps` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du camp',
  `carte_id` tinyint(3) unsigned NOT NULL COMMENT 'Identifiant du plan ou ce camp respawn',
  `nom` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom du camp',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Texte de description du camp',
  PRIMARY KEY (`id`),
  KEY `FK_Camps_Carte` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `caracs`
--

DROP TABLE IF EXISTS `caracs`;
CREATE TABLE IF NOT EXISTS `caracs` (
  `perso_id` int(10) unsigned NOT NULL COMMENT 'Identifiant du personnage',
  `px` mediumint(9) NOT NULL COMMENT 'Exp',
  `pi` mediumint(9) NOT NULL COMMENT 'Points d''investissement du perso',
  `pv` mediumint(9) NOT NULL COMMENT 'PVs actuels du perso',
  `niv_pv` smallint(5) unsigned NOT NULL,
  `recup_pv` smallint(6) NOT NULL,
  `malus_def` smallint(6) NOT NULL,
  `niv_recup_pv` tinyint(3) unsigned NOT NULL,
  `niv` tinyint(3) unsigned NOT NULL COMMENT 'niveau de magie',
  `cercle` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mouv` mediumint(9) NOT NULL,
  `niv_mouv` tinyint(3) unsigned NOT NULL,
  `pa` float NOT NULL,
  `pa_dec` tinyint(4) NOT NULL DEFAULT '0',
  `niv_pa` smallint(5) unsigned NOT NULL,
  `des_attaque` tinyint(3) unsigned NOT NULL,
  `maj_des` tinyint(1) NOT NULL DEFAULT '0',
  `maj_esq_mag` tinyint(4) NOT NULL DEFAULT '0',
  `niv_des` smallint(5) unsigned NOT NULL,
  `force` mediumint(9) NOT NULL,
  `niv_force` smallint(5) unsigned NOT NULL,
  `perception` mediumint(9) NOT NULL,
  `niv_perception` smallint(5) unsigned NOT NULL,
  `res_mag` mediumint(9) NOT NULL DEFAULT '0',
  `esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`perso_id`),
  KEY `IX_Caracs_Px` (`px`),
  KEY `IX_Caracs_Pv` (`pv`),
  KEY `IX_Caracs_MalusDef` (`malus_def`),
  KEY `IX_Caracs_NivDes` (`niv_des`),
  KEY `IX_Caracs_DesAttaque` (`des_attaque`),
  KEY `IX_Caracs_ResMag` (`res_mag`),
  KEY `IX_Caracs_EsqMag` (`esq_mag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter`
--

DROP TABLE IF EXISTS `caracs_alter`;
CREATE TABLE IF NOT EXISTS `caracs_alter` (
  `perso_id` int(10) unsigned NOT NULL,
  `alter_pa` mediumint(8) NOT NULL,
  `alter_pv` mediumint(9) NOT NULL,
  `alter_mouv` mediumint(8) NOT NULL,
  `alter_def` mediumint(8) NOT NULL,
  `alter_att` mediumint(8) NOT NULL,
  `alter_recup_pv` mediumint(8) NOT NULL,
  `alter_force` mediumint(8) NOT NULL,
  `alter_perception` mediumint(8) NOT NULL,
  `nb_desaffil` mediumint(8) NOT NULL,
  `alter_niv_mag` mediumint(8) NOT NULL,
  `alter_effet` int(11) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`perso_id`),
  KEY `IX_CaracsAlter_ResMag` (`alter_res_mag`),
  KEY `IX_CaracsAlter_EsqMag` (`alter_esq_mag`),
  KEY `IX_CaracsAlter_Def` (`alter_def`),
  KEY `IX_CaracsAlter_Att` (`alter_att`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_affi`
--

DROP TABLE IF EXISTS `caracs_alter_affi`;
CREATE TABLE IF NOT EXISTS `caracs_alter_affi` (
  `perso_id` int(10) unsigned NOT NULL,
  `alter_pa` mediumint(8) NOT NULL,
  `alter_pv` mediumint(9) NOT NULL,
  `alter_mouv` mediumint(8) NOT NULL,
  `alter_def` mediumint(8) NOT NULL,
  `alter_att` mediumint(8) NOT NULL,
  `alter_recup_pv` mediumint(8) NOT NULL,
  `alter_force` mediumint(8) NOT NULL,
  `alter_perception` mediumint(8) NOT NULL,
  `nb_desaffil` mediumint(8) NOT NULL,
  `alter_niv_mag` mediumint(8) NOT NULL,
  `alter_effet` int(11) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`perso_id`),
  KEY `IX_CaracsAlter_ResMag` (`alter_res_mag`),
  KEY `IX_CaracsAlter_EsqMag` (`alter_esq_mag`),
  KEY `IX_CaracsAlter_Def` (`alter_def`),
  KEY `IX_CaracsAlter_Att` (`alter_att`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_artefact`
--

DROP TABLE IF EXISTS `caracs_alter_artefact`;
CREATE TABLE IF NOT EXISTS `caracs_alter_artefact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_artefact_id` int(11) NOT NULL,
  `alter_pa` mediumint(9) NOT NULL,
  `alter_pv` mediumint(9) NOT NULL,
  `alter_mouv` mediumint(9) NOT NULL,
  `alter_def` mediumint(9) NOT NULL,
  `alter_att` mediumint(9) NOT NULL,
  `alter_recup_pv` mediumint(9) NOT NULL,
  `alter_force` mediumint(9) NOT NULL,
  `alter_perception` mediumint(9) NOT NULL,
  `alter_niv_mag` mediumint(9) NOT NULL DEFAULT '0',
  `immunite` mediumint(9) NOT NULL,
  `alter_effet` int(11) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `case_artefact_id` (`case_artefact_id`),
  KEY `IX_CaracsAlterArtefact_Def` (`alter_def`),
  KEY `IX_CaracsAlterArtefact_Att` (`alter_att`),
  KEY `IX_CaracsAlterArtefact_ResMag` (`alter_res_mag`),
  KEY `IX_CaracsAlterArtefact_EsqMag` (`alter_esq_mag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_camp`
--

DROP TABLE IF EXISTS `caracs_alter_camp`;
CREATE TABLE IF NOT EXISTS `caracs_alter_camp` (
  `camp_id` tinyint(3) unsigned NOT NULL,
  `alter_pa` mediumint(8) NOT NULL,
  `alter_pv` mediumint(9) NOT NULL,
  `alter_mouv` mediumint(8) NOT NULL,
  `alter_def` mediumint(8) NOT NULL,
  `alter_att` mediumint(8) NOT NULL,
  `alter_recup_pv` mediumint(8) NOT NULL,
  `alter_force` mediumint(8) NOT NULL,
  `alter_perception` mediumint(8) NOT NULL,
  `alter_niv_mag` mediumint(8) NOT NULL,
  `alter_effet` int(11) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`camp_id`),
  KEY `IX_CaracsAlterCamp_Att` (`alter_att`),
  KEY `IX_CaracsAlterCamp_Def` (`alter_def`),
  KEY `IX_CaracsAlterCamp_EsqMag` (`alter_esq_mag`),
  KEY `IX_CaracsAlterCamp_ResMag` (`alter_res_mag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_ext`
--

DROP TABLE IF EXISTS `caracs_alter_ext`;
CREATE TABLE IF NOT EXISTS `caracs_alter_ext` (
  `grade_id` smallint(8) NOT NULL,
  `cout_pv` int(11) NOT NULL,
  `alter_pa_min` smallint(8) NOT NULL,
  `alter_pa_max` smallint(8) NOT NULL,
  `alter_mouv_min` smallint(8) NOT NULL,
  `alter_mouv_max` smallint(8) NOT NULL,
  `alter_def_min` smallint(8) NOT NULL,
  `alter_def_max` smallint(8) NOT NULL,
  `alter_att_min` smallint(8) NOT NULL,
  `alter_att_max` smallint(8) NOT NULL,
  `alter_recup_pv_min` smallint(8) NOT NULL,
  `alter_recup_pv_max` smallint(8) NOT NULL,
  `alter_force_min` smallint(8) NOT NULL,
  `alter_force_max` smallint(8) NOT NULL,
  `alter_perception_min` smallint(8) NOT NULL,
  `alter_perception_max` smallint(8) NOT NULL,
  `alter_niv_mag_min` smallint(8) NOT NULL,
  `alter_niv_mag_max` smallint(8) NOT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_mag`
--

DROP TABLE IF EXISTS `caracs_alter_mag`;
CREATE TABLE IF NOT EXISTS `caracs_alter_mag` (
  `unique_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `perso_id` int(10) unsigned NOT NULL,
  `alter_pa` mediumint(9) NOT NULL DEFAULT '0',
  `alter_pv` mediumint(9) NOT NULL DEFAULT '0',
  `alter_mouv` mediumint(9) NOT NULL DEFAULT '0',
  `alter_def` mediumint(9) NOT NULL DEFAULT '0',
  `alter_att` mediumint(9) NOT NULL DEFAULT '0',
  `alter_recup_pv` mediumint(9) NOT NULL DEFAULT '0',
  `alter_force` mediumint(9) NOT NULL DEFAULT '0',
  `alter_perception` mediumint(9) NOT NULL DEFAULT '0',
  `alter_niv_mag` mediumint(9) NOT NULL DEFAULT '0',
  `alter_effet` mediumint(9) NOT NULL DEFAULT '0',
  `immunite` smallint(6) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  `alter_res_phy` int(11) NOT NULL DEFAULT '0',
  `nb_tour` tinyint(3) NOT NULL DEFAULT '1',
  `cassable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dissipe_mort` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`unique_id`),
  KEY `IX_CaracsAlterMagie_Def` (`alter_def`),
  KEY `IX_CaracsAlterMagie_Att` (`alter_att`),
  KEY `IX_CaracsAlterMagie_ResMag` (`alter_res_mag`),
  KEY `IX_CaracsAlterMagie_EsqMag` (`alter_esq_mag`),
  KEY `IX_CaracsAlterMagie_Perso` (`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `caracs_alter_plan`
--

DROP TABLE IF EXISTS `caracs_alter_plan`;
CREATE TABLE IF NOT EXISTS `caracs_alter_plan` (
  `perso_id` int(10) unsigned NOT NULL,
  `alter_pa` int(11) NOT NULL,
  `alter_pv` int(11) NOT NULL,
  `alter_mouv` int(11) NOT NULL,
  `alter_def` int(11) NOT NULL,
  `alter_att` int(11) NOT NULL,
  `alter_recup_pv` int(11) NOT NULL,
  `alter_force` int(11) NOT NULL,
  `alter_perception` int(11) NOT NULL,
  `alter_niv_mag` int(11) NOT NULL,
  `alter_effet` int(11) NOT NULL DEFAULT '0',
  `alter_res_mag` int(11) NOT NULL DEFAULT '0',
  `alter_esq_mag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`perso_id`),
  KEY `IX_CaracsAlterPlan_Def` (`alter_def`),
  KEY `IX_CaracsAlterPlan_Att` (`alter_att`),
  KEY `IX_CaracsAlterPlan_ResMag` (`alter_res_mag`),
  KEY `IX_CaracsAlterPlan_EsqMag` (`alter_esq_mag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

DROP TABLE IF EXISTS `cartes`;
CREATE TABLE IF NOT EXISTS `cartes` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du plan',
  `nom` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom de ce plan',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description du plan',
  `circ` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00' COMMENT 'Defini la circularité du plan. Le premier bit en X le second en Y. 1 = circulaire.',
  `infini` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000' COMMENT 'Defini si un côté est infini ou non. Les deux premières valeurs en X, les deux dernières en Y. Première valeur en X pour les valeures négatives de X, vaut 1 si infini.',
  `x_min` mediumint(6) NOT NULL DEFAULT '-10',
  `y_min` mediumint(6) NOT NULL DEFAULT '-10',
  `x_max` mediumint(9) NOT NULL DEFAULT '10',
  `y_max` mediumint(9) NOT NULL DEFAULT '10',
  `visible_x_min` mediumint(6) NOT NULL COMMENT 'Debut carte visible en X',
  `visible_x_max` mediumint(6) NOT NULL COMMENT 'Fin carte visible en X',
  `visible_y_min` mediumint(6) NOT NULL COMMENT 'Debut carte visible en Y',
  `visible_y_max` mediumint(6) NOT NULL COMMENT 'Fin carte visible en X',
  `dla` float NOT NULL DEFAULT '23' COMMENT 'en heures',
  `nom_decors` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `case_artefact`
--

DROP TABLE IF EXISTS `case_artefact`;
CREATE TABLE IF NOT EXISTS `case_artefact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `pv_max` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rarete` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'en %, 1 rare, 100 courant',
  `cout` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'cout de l''objet en po',
  `poid` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'poid de l''objet en kilo',
  `categorie_id` mediumint(8) NOT NULL,
  `consom` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0 : ni consomable ni activable,1 : activable,2 : activ� en permanence,3 : consomable,4 : en cours de consomation',
  PRIMARY KEY (`id`),
  KEY `FK_CaseArtefact_CategorieArtefact` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `case_objet_complexe`
--

DROP TABLE IF EXISTS `case_objet_complexe`;
CREATE TABLE IF NOT EXISTS `case_objet_complexe` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `pv_max` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bloquant` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:bloquant',
  `reparable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Défini si un objet complexe est réparable ou non. Par défaut il ne l''est pas.',
  `images` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom s',
  `taille_x` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `taille_y` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `categorie_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_CaseObjetComplexe_CategorieObjetComplexe` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `case_objet_simple`
--

DROP TABLE IF EXISTS `case_objet_simple`;
CREATE TABLE IF NOT EXISTS `case_objet_simple` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `bloquant` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:bloquant,0:non',
  `pv_max` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pv maximum de l''objet',
  `poid` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `image` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'defaut' COMMENT 'nom de l''image',
  `categorie_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_CaseObjetSimple_CategorieObjetSimple` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `case_terrain`
--

DROP TABLE IF EXISTS `case_terrain`;
CREATE TABLE IF NOT EXISTS `case_terrain` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom du type de terrain',
  `image` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom de l''image',
  `couleur` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'couleur Hexa',
  `mouv` smallint(6) NOT NULL COMMENT 'nb de mouv',
  `categorie_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_CaseTerrain_CategorieTerrain` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_artefact`
--

DROP TABLE IF EXISTS `categorie_artefact`;
CREATE TABLE IF NOT EXISTS `categorie_artefact` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_objet_complexe`
--

DROP TABLE IF EXISTS `categorie_objet_complexe`;
CREATE TABLE IF NOT EXISTS `categorie_objet_complexe` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_objet_simple`
--

DROP TABLE IF EXISTS `categorie_objet_simple`;
CREATE TABLE IF NOT EXISTS `categorie_objet_simple` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_terrain`
--

DROP TABLE IF EXISTS `categorie_terrain`;
CREATE TABLE IF NOT EXISTS `categorie_terrain` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `classement`
--

DROP TABLE IF EXISTS `classement`;
CREATE TABLE IF NOT EXISTS `classement` (
  `date` date NOT NULL,
  `mat` int(11) NOT NULL,
  `pseudo` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `race` smallint(6) NOT NULL,
  `camp` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `galon` tinyint(4) NOT NULL,
  `xp` mediumint(9) NOT NULL,
  `meurtre` smallint(6) NOT NULL,
  `mort` smallint(6) NOT NULL,
  `joueur` int(10) unsigned NOT NULL,
  `nom_race` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`date`,`mat`),
  KEY `classement_cache_xp` (`xp`),
  KEY `classement_cache_mort` (`meurtre`),
  KEY `classement_cache_meurtre` (`mort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table d''archivage du classement';

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `classement_view`
--
DROP VIEW IF EXISTS `classement_view`;
CREATE TABLE IF NOT EXISTS `classement_view` (
`date` date
,`id` int(10) unsigned
,`nom` varchar(64)
,`race` int(10) unsigned
,`camp` tinyint(3) unsigned
,`type` int(11)
,`grade` smallint(6)
,`galon` smallint(6)
,`px` mediumint(9)
,`tueur` bigint(21)
,`mort` bigint(21)
,`joueur` int(10) unsigned
,`nom_race` varchar(100)
);
-- --------------------------------------------------------

--
-- Structure de la table `classes`
--
DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `Id` varchar(2) NOT NULL,
  `Camps` tinyint(3) unsigned NOT NULL,
  `Position` tinyint(1) NOT NULL,
  `Titre` varchar(40) NOT NULL,
  `Sub` varchar(50) NOT NULL,
  `Description` varchar(250) NOT NULL DEFAULT '''''',
  PRIMARY KEY (`Id`,`Camps`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
--
-- Structure de la table `damier_artefact`
--

DROP TABLE IF EXISTS `damier_artefact`;
CREATE TABLE IF NOT EXISTS `damier_artefact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icone_artefact_id` mediumint(9) NOT NULL,
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `pv` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UU_DamierArtefact_PosX_PosY_CarteId` (`pos_x`,`pos_y`,`carte_id`),
  KEY `FK_DamierArtefact_Carte` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_bouclier`
--

DROP TABLE IF EXISTS `damier_bouclier`;
CREATE TABLE IF NOT EXISTS `damier_bouclier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom du bouclier',
  `nom_image` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom de l''image du bouclier',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rp  du bouclier',
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `type_id` tinyint(4) NOT NULL COMMENT 'Niveau du bouclier généré',
  `objet_lie` int(11) NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL COMMENT 'plan ou se trouve  le bouclier',
  `pv` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pv en cours',
  `pv_max` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pv maximum  du bouclier',
  `deplacer` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: non 1:oui',
  `statut` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:ouvert, 0:ferm',
  PRIMARY KEY (`id`),
  KEY `FK_DamierBouclier_Carte` (`carte_id`),
  KEY `FK_DamierBouclier_DamierObjetComplexe` (`objet_lie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_objet_complexe`
--

DROP TABLE IF EXISTS `damier_objet_complexe`;
CREATE TABLE IF NOT EXISTS `damier_objet_complexe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_objet_complexe_id` mediumint(9) NOT NULL,
  `pos_x` int(11) NOT NULL,
  `pos_x_max` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `pos_y_max` int(11) NOT NULL,
  `pv` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_DamierObjetComplexe_CaseObjetComplexe` (`case_objet_complexe_id`),
  KEY `FK_DamierObjetComplexe_Carte` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_objet_simple`
--

DROP TABLE IF EXISTS `damier_objet_simple`;
CREATE TABLE IF NOT EXISTS `damier_objet_simple` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_objet_simple_id` mediumint(9) NOT NULL,
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `pv` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_DamierObjetSimple_Carte` (`carte_id`),
  KEY `FK_DamierObjetSimple_CaseObjetSimple` (`case_objet_simple_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_persos`
--

DROP TABLE IF EXISTS `damier_persos`;
CREATE TABLE IF NOT EXISTS `damier_persos` (
  `carte_id` tinyint(3) unsigned NOT NULL COMMENT 'identifiant de la carte',
  `pos_x` int(11) NOT NULL COMMENT 'position x sur le damier',
  `pos_y` int(11) NOT NULL COMMENT 'position y sur le damier',
  `perso_id` int(10) unsigned NOT NULL COMMENT 'id du personnage',
  PRIMARY KEY (`carte_id`,`pos_x`,`pos_y`),
  KEY `FK_DamierPersos_Persos` (`perso_id`),
  KEY `FK_DamierPersos_Carte` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `damier_porte`
--

DROP TABLE IF EXISTS `damier_porte`;
CREATE TABLE IF NOT EXISTS `damier_porte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom de la porte',
  `nom_image` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom de l''image de la porte',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rp de la porte',
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `porte_liee_id` int(11) DEFAULT NULL,
  `objet_lie` int(11) NOT NULL,
  `spawn_id` int(11) NOT NULL COMMENT 'la ou la porte mene',
  `carte_id` tinyint(3) unsigned NOT NULL COMMENT 'plan ou se trouve la porte',
  `pv` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pv en cours',
  `pv_max` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pv maximum de la porte',
  `statut` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:ouvert, 0:fermé',
  PRIMARY KEY (`id`),
  KEY `FK_DamierPorte_Carte` (`carte_id`),
  KEY `FK_DamierPorte_DamierSpawn` (`spawn_id`),
  KEY `FK_DamierPorte_PorteLiee` (`porte_liee_id`),
  KEY `FK_DamierPorte_DamierObjetComplexe` (`objet_lie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_spawn`
--

DROP TABLE IF EXISTS `damier_spawn`;
CREATE TABLE IF NOT EXISTS `damier_spawn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `pos_max_x` int(11) NOT NULL,
  `pos_max_y` int(11) NOT NULL,
  `carte_id` tinyint(3) unsigned NOT NULL,
  `primaire` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_DamierSpawn_Carte` (`carte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `damier_terrain`
--

DROP TABLE IF EXISTS `damier_terrain`;
CREATE TABLE IF NOT EXISTS `damier_terrain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carte_id` tinyint(3) unsigned NOT NULL COMMENT 'identifiant de la carte',
  `terrain_id` mediumint(9) unsigned NOT NULL COMMENT 'id du terrain avec images',
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_DamierTerrain_CaseTerrain` (`terrain_id`),
  KEY `FK_DamierTerrain_Carte` (`carte_id`),
  KEY `IX_DamierTerrain_PosX` (`pos_x`),
  KEY `IX_DamierTerrain_PosY` (`pos_y`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `effet`
--

DROP TABLE IF EXISTS `effet`;
CREATE TABLE IF NOT EXISTS `effet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_effet` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Element sur lequel porte l''effet : PV, PA, PM; ou objet s''il s''agit de ramasser.',
  `effet` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'valeur de l''effet, negatif s''il s''agit d''un retrait de carac, positif s''il s''agit d''un ajout, nul si prise en compte d''une carac du lanceur.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UU_Effet_Type_Valeur` (`type_effet`,`effet`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=227 ;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''evenement',
  `evenement_type_id` tinyint(3) unsigned NOT NULL COMMENT 'Identifiant du type d''evenement',
  `perso_id` int(10) unsigned NOT NULL COMMENT 'Identifiant du personnage',
  `second_id` int(11) NOT NULL,
  `second_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL COMMENT 'Date de l''evenement',
  `champs` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'variable de l''évenements sous la forme : champ0,champ1,champ2',
  PRIMARY KEY (`id`),
  KEY `perso_id` (`perso_id`),
  KEY `type_evenement_id` (`evenement_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perso_source` int(10) unsigned NOT NULL,
  `type_source` tinyint(1) NOT NULL DEFAULT '0',
  `id_perso_desti` int(10) unsigned DEFAULT NULL,
  `type_desti` tinyint(1) DEFAULT '0',
  `id_event` int(10) unsigned DEFAULT NULL,
  `date_ev` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type_ev` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'basic',
  `public_data` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_data` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `result` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_src` (`id_perso_source`),
  KEY `id_dst` (`id_perso_desti`),
  KEY `id_ev` (`id_event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `evenements_texte`
--

DROP TABLE IF EXISTS `evenements_texte`;
CREATE TABLE IF NOT EXISTS `evenements_texte` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `texte` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `texte` (`texte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `evenement_type`
--

DROP TABLE IF EXISTS `evenement_type`;
CREATE TABLE IF NOT EXISTS `evenement_type` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du type d''evenement',
  `type` enum('mouvement','attaque','esquive','sort','esquive_magique','sprint','suicide','entrainement','transaction','mort','meurtre','grade_up','grade_down','faction_in','faction_out','faction_eject','perso') COLLATE utf8_unicode_ci NOT NULL COMMENT 'si le champ est noté ''perso'' il faut afficher le champs champs de la table evenement sans traitement.',
  `motif` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Motif du texte a trou, champs vide par [champ0]',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factions`
--

DROP TABLE IF EXISTS `factions`;
CREATE TABLE IF NOT EXISTS `factions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de la faction',
  `nom` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom de la faction',
  `race` tinyint(3) unsigned NOT NULL COMMENT 'correspond au camp',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description de la faction',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `alignement` int(10) unsigned NOT NULL,
  `type_nom` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL COMMENT 'Date de creation de la faction',
  `site_url` tinytext COLLATE utf8_unicode_ci COMMENT 'URL du site de la faction si il y a lieu',
  `logo_url` tinytext COLLATE utf8_unicode_ci COMMENT 'Image du logo de la faction',
  `nature` enum('LEGION','ORDRE') COLLATE utf8_unicode_ci NOT NULL,
  `link1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link5` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Factions_Camps` (`race`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `faction_alignement`
--

DROP TABLE IF EXISTS `faction_alignement`;
CREATE TABLE IF NOT EXISTS `faction_alignement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `faction_grades`
--

DROP TABLE IF EXISTS `faction_grades`;
CREATE TABLE IF NOT EXISTS `faction_grades` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du grade',
  `grade_id` mediumint(9) NOT NULL COMMENT 'Identifiant du grade',
  `faction_id` mediumint(8) unsigned NOT NULL COMMENT 'Identifiant de la faction',
  `nom` varchar(42) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom du grade dans cette faction',
  `description` mediumtext COLLATE utf8_unicode_ci COMMENT 'Description de ce grade au sein de cette faction',
  `droits` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00000001' COMMENT 'Droit de ce grade dans la faction',
  PRIMARY KEY (`id`),
  KEY `FK_FactionGrade_Factions` (`faction_id`),
  KEY `IX_FactionGrades_GradeId` (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `faction_membres`
--

DROP TABLE IF EXISTS `faction_membres`;
CREATE TABLE IF NOT EXISTS `faction_membres` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identification du membre',
  `perso_id` int(10) unsigned NOT NULL COMMENT 'Identifiant du personnage',
  `faction_id` mediumint(8) unsigned NOT NULL COMMENT 'Identifiant de la faction',
  `faction_grade_id` mediumint(8) unsigned DEFAULT NULL COMMENT 'Identifiant du grade de faction',
  PRIMARY KEY (`id`),
  KEY `FK_FactionMembres_Persos` (`perso_id`),
  KEY `FK_FactionMembres_Factions` (`faction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `faction_types`
--

DROP TABLE IF EXISTS `faction_types`;
CREATE TABLE IF NOT EXISTS `faction_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `grimoire`
--

DROP TABLE IF EXISTS `grimoire`;
CREATE TABLE IF NOT EXISTS `grimoire` (
  `id_perso` int(10) unsigned NOT NULL,
  `id_sort` mediumint(9) NOT NULL,
  PRIMARY KEY (`id_perso`,`id_sort`),
  KEY `FK_Grimoire_Persos` (`id_perso`),
  KEY `FK_Grimoire_Action` (`id_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `icone_galons`
--

DROP TABLE IF EXISTS `icone_galons`;
CREATE TABLE IF NOT EXISTS `icone_galons` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du galon',
  `grade_id` int(11) NOT NULL,
  `icone_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL du galon autiliser',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `icone_persos`
--

DROP TABLE IF EXISTS `icone_persos`;
CREATE TABLE IF NOT EXISTS `icone_persos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''icone',
  `camp_id` int(10) unsigned NOT NULL COMMENT 'Identifiant du camp',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `grade_id` int(11) NOT NULL,
  `sexe_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `xp_min` mediumint(9) NOT NULL COMMENT 'Niveau d''xp a partir duquel s''applique cet icone',
  `xp_max` mediumint(9) NOT NULL,
  `icone_url` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL de l''icone à  utiliser',
  KEY `id` (`id`),
  KEY `camp_id` (`camp_id`),
  KEY `FK_IconePersos_Sexe` (`sexe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

DROP TABLE IF EXISTS `inventaire`;
CREATE TABLE IF NOT EXISTS `inventaire` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perso_id` int(10) unsigned NOT NULL,
  `case_artefact_id` int(11) NOT NULL,
  `statut` enum('actif','inactif') COLLATE utf8_unicode_ci NOT NULL,
  `pv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `consom` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0 : ni consomable ni activable,1 : activable,2 : activ� en permanence,3 : consomable,4 : en cours de consomation',
  PRIMARY KEY (`id`),
  KEY `FK_Inventaires_Persos` (`perso_id`),
  KEY `FK_Inventaires_CaseArtefact` (`case_artefact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--

DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `distribue` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `logs_admin`
--

DROP TABLE IF EXISTS `logs_admin`;
CREATE TABLE IF NOT EXISTS `logs_admin` (
  `perso_id` int(11) NOT NULL COMMENT 'id du personnage modifi�/consult�',
  `admin_id` int(11) NOT NULL COMMENT 'id de l''admin/anim/at',
  `message` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'message de ce qui � �t� fait.',
  `date` datetime NOT NULL,
  KEY `IX_LogsAdmin_Perso` (`perso_id`),
  KEY `IX_LogsAdmin_AdminId` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medailles`
--

DROP TABLE IF EXISTS `medailles`;
CREATE TABLE IF NOT EXISTS `medailles` (
  `id_perso` int(10) unsigned NOT NULL,
  `id_medaille` smallint(5) unsigned NOT NULL,
  `nombre` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_perso`,`id_medaille`),
  KEY `FK_Medailles_Persos` (`id_perso`),
  KEY `FK_Medailles_MedaillesListe` (`id_medaille`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medailles_liste`
--

DROP TABLE IF EXISTS `medailles_liste`;
CREATE TABLE IF NOT EXISTS `medailles_liste` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `niveau` tinyint(3) unsigned NOT NULL COMMENT '1 = platine, 2 = or 3 = argent 4 = bronze 5 = chocolat',
  `priorite` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'la priorite d''affichage pour un même niveau de médaille',
  `image` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'indique une image à afficher',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `morgue`
--

DROP TABLE IF EXISTS `morgue`;
CREATE TABLE IF NOT EXISTS `morgue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perso` int(10) unsigned NOT NULL,
  `nom_perso` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `race_perso` int(10) unsigned NOT NULL,
  `nom_race_perso` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade_perso` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mat_victime` int(10) unsigned NOT NULL,
  `nom_victime` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `race_victime` int(10) unsigned NOT NULL,
  `nom_race_victime` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade_victime` int(10) unsigned NOT NULL,
  `plan_victime` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Terre',
  PRIMARY KEY (`id`),
  KEY `id_perso` (`id_perso`),
  KEY `id_victime` (`mat_victime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom exp, mat exp, mat perso receveur ( soit un de nos persos )',
  `lien` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flag_lu` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Notifications_Utilisateurs` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `persos`
--

DROP TABLE IF EXISTS `persos`;
CREATE TABLE IF NOT EXISTS `persos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant du personnage',
  `background` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `description_affil` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `classe` tinyint(3) unsigned DEFAULT NULL COMMENT '10ène = Ordre, unité = Caveau',
  `utilisateur_id` int(10) unsigned NOT NULL COMMENT 'Identifiant de l''utilisateur',
  `nb_suicide` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'nombre de suicide du perso',
  `race_id` int(10) unsigned NOT NULL COMMENT 'Identifiant de la race',
  `superieur_id` int(10) unsigned DEFAULT NULL COMMENT 'Personnage a qui ce personnage est affilié',
  `grade_id` smallint(6) NOT NULL COMMENT 'Identifiant du grade',
  `faction_id` mediumint(8) NOT NULL DEFAULT '0',
  `nom` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Le nom du personnage',
  `titre` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` datetime NOT NULL COMMENT 'Date de creation du personnage',
  `date_tour` datetime NOT NULL COMMENT 'date du prochain tour',
  `date_esquivemagique` datetime NOT NULL,
  `avatar_url` tinytext COLLATE utf8_unicode_ci COMMENT 'URL de l''avatar de ce personnage',
  `icone_id` smallint(5) DEFAULT NULL COMMENT 'id de l''icone personnalisée du perso, si jamais il y a',
  `galon_id` smallint(6) NOT NULL DEFAULT '0' COMMENT 'id du galon assigne au perso',
  `alter_spawn` smallint(6) NOT NULL DEFAULT '0',
  `options` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000000' COMMENT 'signature, possede un pet, vois les pets, option reception bal, html ou txt',
  `mdj` text COLLATE utf8_unicode_ci COMMENT 'message du jour du personnage',
  `signature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe` tinyint(1) unsigned DEFAULT NULL,
  `pewo` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'monnaie',
  `nom_race` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Si non-null, remplace le nom de la race du jeu',
  `pnj` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = pj, 1 = pnj',
  `mortel` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = immortel, 1 = mortel en vie, -1 = mortel décédé, 2 = mortel en attente de la première incarnation',
  PRIMARY KEY (`id`),
  KEY `IX_Persos_GradeId` (`grade_id`),
  KEY `IX_Persos_GalonId` (`galon_id`),
  KEY `FK_Persos_SuperieurId` (`superieur_id`),
  KEY `FK_Persos_UtilisateurId` (`utilisateur_id`),
  KEY `FK_Persos_RacesId` (`race_id`),
  KEY `FK_Persos_Sexe` (`sexe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `persos_familier`
--

DROP TABLE IF EXISTS `persos_familier`;
CREATE TABLE IF NOT EXISTS `persos_familier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `persos_id` int(10) unsigned NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_PersosFamilier_Persos` (`persos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Possession d''un familier' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `persos_ia`
--

DROP TABLE IF EXISTS `persos_ia`;
CREATE TABLE IF NOT EXISTS `persos_ia` (
  `id` int(10) unsigned NOT NULL COMMENT 'matricule',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'prochain tour',
  `dna` text NOT NULL COMMENT 'ADN du pnj',
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `persos_mdj`
--

DROP TABLE IF EXISTS `persos_mdj`;
CREATE TABLE IF NOT EXISTS `persos_mdj` (
  `id` int(8) unsigned NOT NULL COMMENT 'timestamp / 119 (1m59s)',
  `perso_id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` varchar(250) NOT NULL,
  PRIMARY KEY (`id`,`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Messages du jours style Twitter';

-- --------------------------------------------------------

--
-- Structure de la table `races`
--

DROP TABLE IF EXISTS `races`;
CREATE TABLE IF NOT EXISTS `races` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `race_id` tinyint(3) unsigned NOT NULL,
  `grade_id` int(11) DEFAULT NULL COMMENT 'Identifiant du camp',
  `camp_id` tinyint(3) unsigned NOT NULL,
  `type` int(11) NOT NULL DEFAULT '3' COMMENT 'Type de jeu, 3 ou 7 cases, 0 pour parias',
  `nom` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom de la race',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description de la race',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL COMMENT 'couleur de la race',
  PRIMARY KEY (`id`),
  KEY `IX_Races_RaceId` (`race_id`),
  KEY `IX_Races_GradeId` (`grade_id`),
  KEY `FK_Races_Camps` (`camp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `record`
--

DROP TABLE IF EXISTS `record`;
CREATE TABLE IF NOT EXISTS `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `perso_id` int(10) unsigned NOT NULL,
  `valeur` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Record_Persos` (`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='records realise par les joueurs d ewo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `repertoire`
--

DROP TABLE IF EXISTS `repertoire`;
CREATE TABLE IF NOT EXISTS `repertoire` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perso_id` int(10) unsigned NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UU_Repertoire_PersoContact` (`perso_id`,`contact_id`),
  KEY `FK_Repertoire_Contact` (`contact_id`),
  KEY `FK_Repertoire_Persos` (`perso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sexe`
--

DROP TABLE IF EXISTS `sexe`;
CREATE TABLE IF NOT EXISTS `sexe` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `sexe` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `stat_popvivante`
--

DROP TABLE IF EXISTS `stat_popvivante`;
CREATE TABLE IF NOT EXISTS `stat_popvivante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT NULL,
  `nb_joueur_total` int(11) DEFAULT NULL,
  `ange_g0_terre` int(11) DEFAULT NULL,
  `ange_g0_enfer` int(11) DEFAULT NULL,
  `ange_g0_paradis` int(11) DEFAULT NULL,
  `ange_g1_terre` int(11) DEFAULT NULL,
  `ange_g1_enfer` int(11) DEFAULT NULL,
  `ange_g1_paradis` int(11) DEFAULT NULL,
  `ange_g2_terre` int(11) DEFAULT NULL,
  `ange_g2_enfer` int(11) DEFAULT NULL,
  `ange_g2_paradis` int(11) DEFAULT NULL,
  `ange_g3_terre` int(11) DEFAULT NULL,
  `ange_g3_enfer` int(11) DEFAULT NULL,
  `ange_g3_paradis` int(11) DEFAULT NULL,
  `ange_g4_terre` int(11) DEFAULT NULL,
  `ange_g4_enfer` int(11) DEFAULT NULL,
  `ange_g4_paradis` int(11) DEFAULT NULL,
  `ange_g5_terre` int(11) DEFAULT NULL,
  `ange_g5_enfer` int(11) DEFAULT NULL,
  `ange_g5_paradis` int(11) DEFAULT NULL,
  `ange_total` int(11) DEFAULT NULL,
  `demon_g0_terre` int(11) DEFAULT NULL,
  `demon_g0_enfer` int(11) DEFAULT NULL,
  `demon_g0_paradis` int(11) DEFAULT NULL,
  `demon_g1_terre` int(11) DEFAULT NULL,
  `demon_g1_enfer` int(11) DEFAULT NULL,
  `demon_g1_paradis` int(11) DEFAULT NULL,
  `demon_g2_terre` int(11) DEFAULT NULL,
  `demon_g2_enfer` int(11) DEFAULT NULL,
  `demon_g2_paradis` int(11) DEFAULT NULL,
  `demon_g3_terre` int(11) DEFAULT NULL,
  `demon_g3_enfer` int(11) DEFAULT NULL,
  `demon_g3_paradis` int(11) DEFAULT NULL,
  `demon_g4_terre` int(11) DEFAULT NULL,
  `demon_g4_enfer` int(11) DEFAULT NULL,
  `demon_g4_paradis` int(11) DEFAULT NULL,
  `demon_g5_terre` int(11) DEFAULT NULL,
  `demon_g5_enfer` int(11) DEFAULT NULL,
  `demon_g5_paradis` int(11) DEFAULT NULL,
  `demon_total` int(11) DEFAULT NULL,
  `humain_g0_terre` int(11) DEFAULT NULL,
  `humain_g0_enfer` int(11) DEFAULT NULL,
  `humain_g0_paradis` int(11) DEFAULT NULL,
  `humain_g1_terre` int(11) DEFAULT NULL,
  `humain_g1_enfer` int(11) DEFAULT NULL,
  `humain_g1_paradis` int(11) DEFAULT NULL,
  `humain_g2_terre` int(11) DEFAULT NULL,
  `humain_g2_enfer` int(11) DEFAULT NULL,
  `humain_g2_paradis` int(11) DEFAULT NULL,
  `humain_g3_terre` int(11) DEFAULT NULL,
  `humain_g3_enfer` int(11) DEFAULT NULL,
  `humain_g3_paradis` int(11) DEFAULT NULL,
  `humain_g4_terre` int(11) DEFAULT NULL,
  `humain_g4_enfer` int(11) DEFAULT NULL,
  `humain_g4_paradis` int(11) DEFAULT NULL,
  `humain_g5_terre` int(11) DEFAULT NULL,
  `humain_g5_enfer` int(11) DEFAULT NULL,
  `humain_g5_paradis` int(11) DEFAULT NULL,
  `humain_total` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_Statistiques_Date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifiant de l''utilisateur',
  `nom` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom (ou pseudo) de l''utilisateur',
  `email` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Adresse email de l''utilisateur',
  `passwd` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Le hash sha1 (ou md5) du mot de pass de l''utilisateur',
  `passwd_forum` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'password hash pour l''interface avec le forum phpbb3',
  `date_enregistrement` datetime NOT NULL COMMENT 'Date d''enregistrement de l''utilisateur',
  `droits` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Les droits de l''utilisateur',
  `options` tinyint(4) DEFAULT '0' COMMENT 'Les options de l''utilisateur',
  `codevalidation` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'code de validation du compte.',
  `session_id` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'session_id unique pour la gestion des APIs',
  `bals_speed` float NOT NULL DEFAULT '0.5',
  `template` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `icones_pack` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grille` tinyint(1) NOT NULL DEFAULT '0',
  `rose` tinyint(1) NOT NULL DEFAULT '1',
  `redirection` tinyint(1) NOT NULL DEFAULT '1',
  `mail_rp` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'L''utilisateur reçoit les mails "RP"',
  `mail_event` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'l''utilisateur reçoit les mails d''événements',
  `mail_bal` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'L''utilisateur reçoit les BAL par mail',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UU_Utilisateurs_Nom` (`nom`),
  UNIQUE KEY `UU_Utilisateurs_Email` (`email`(50)),
  KEY `IX_Utilisateurs_Passwd` (`passwd`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=189 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_ban`
--

DROP TABLE IF EXISTS `utilisateurs_ban`;
CREATE TABLE IF NOT EXISTS `utilisateurs_ban` (
  `utilisateur_id` int(10) unsigned NOT NULL,
  `date` int(11) NOT NULL,
  `date_fin` int(11) NOT NULL,
  `motif` text COLLATE utf8_unicode_ci NOT NULL,
  `statut` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`date`),
  KEY `FK_UtilisateursBan_Utilisateurs` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_ticket`
--

DROP TABLE IF EXISTS `utilisateurs_ticket`;
CREATE TABLE IF NOT EXISTS `utilisateurs_ticket` (
  `utilisateur_id` int(10) unsigned NOT NULL,
  `ticket` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Ticket de connection pour l''autologin',
  `expiration` datetime NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`ticket`),
  KEY `IX_UtilisateurTicket_Id` (`utilisateur_id`),
  KEY `IX_UtilisateurTicket_Ticket` (`ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_vacances`
--

DROP TABLE IF EXISTS `utilisateurs_vacances`;
CREATE TABLE IF NOT EXISTS `utilisateurs_vacances` (
  `utilisateur_id` int(10) unsigned NOT NULL,
  `date_demande` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `date_retour` datetime NOT NULL,
  `traite` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`date_demande`),
  KEY `FK_UtilisateursVacances_Utilisateurs` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wait_affil`
--

DROP TABLE IF EXISTS `wait_affil`;
CREATE TABLE IF NOT EXISTS `wait_affil` (
  `superieur` int(10) unsigned NOT NULL,
  `vassal` int(10) unsigned NOT NULL,
  `vassal_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`superieur`,`vassal`),
  KEY `FK_WaitAffil_Superieur` (`superieur`),
  KEY `FK_WaitAffil_Vassal` (`vassal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wait_faction`
--

DROP TABLE IF EXISTS `wait_faction`;
CREATE TABLE IF NOT EXISTS `wait_faction` (
  `perso_id` int(10) unsigned NOT NULL,
  `faction_id` mediumint(8) unsigned NOT NULL,
  `demandeur` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Vaut 1 si l''utilisateur est le demandeur, 0 si c''est la faction',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_WaitFaction_Persos` (`perso_id`),
  KEY `FK_WaitFaction_Factions` (`faction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la vue `bals_listeview`
--
DROP TABLE IF EXISTS `bals_listeview`;

CREATE VIEW `bals_listeview` AS select `bals_listes`.`id` AS `id`,`bals_listes`.`libelle` AS `libelle`,`bals_listes`.`ouverture` AS `ouverture`,`bals_listes`.`type` AS `type`,`bals_listes`.`camp` AS `camp`,`bals_listes`.`owner` AS `owner`,`bals_listes`.`liste` AS `liste`,`persos`.`grade_id` AS `grade`,`damier_persos`.`pos_x` AS `pos_x`,`damier_persos`.`pos_y` AS `pos_y`,`damier_persos`.`carte_id` AS `carte` from ((`bals_listes` left join `persos` on((`persos`.`id` = `bals_listes`.`owner`))) left join `damier_persos` on((`damier_persos`.`perso_id` = `persos`.`id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `classement_view`
--
DROP TABLE IF EXISTS `classement_view`;

CREATE VIEW `classement_view` AS select curdate() AS `date`,`persos`.`id` AS `id`,`persos`.`nom` AS `nom`,`persos`.`race_id` AS `race`,`races`.`camp_id` AS `camp`,`races`.`type` AS `type`,`persos`.`grade_id` AS `grade`,`persos`.`galon_id` AS `galon`,`caracs`.`px` AS `px`,(select count(0) AS `COUNT( * )` from `morgue` where (`morgue`.`id_perso` = `persos`.`id`)) AS `tueur`,(select count(0) AS `COUNT( * )` from `morgue` where (`morgue`.`mat_victime` = `persos`.`id`)) AS `mort`,`persos`.`utilisateur_id` AS `joueur`,`persos`.`nom_race` AS `nom_race` from ((`persos` join `caracs` on((`caracs`.`perso_id` = `persos`.`id`))) join `races` on(((`races`.`race_id` = `persos`.`race_id`) and (`races`.`grade_id` = 0)))) where (`races`.`camp_id` < 5) group by `persos`.`id` order by `persos`.`id`;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `api_key`
--
ALTER TABLE `api_key`
  ADD CONSTRAINT `api_key_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Contraintes pour la table `at_log_inter_geo`
--
ALTER TABLE `at_log_inter_geo`
  ADD CONSTRAINT `at_log_inter_geo_ibfk_1` FOREIGN KEY (`id`) REFERENCES `at_log` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `at_log_inter_geo_ibfk_2` FOREIGN KEY (`id_perso1`) REFERENCES `persos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `at_log_inter_geo_ibfk_3` FOREIGN KEY (`id_perso2`) REFERENCES `persos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `at_log_inter_geo_ibfk_4` FOREIGN KEY (`ref`) REFERENCES `at_log_inter_geo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `at_log_inter_geo_ibfk_5` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`);

--
-- Contraintes pour la table `at_members`
--
ALTER TABLE `at_members`
  ADD CONSTRAINT `at_members_ibfk_1` FOREIGN KEY (`id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bals`
--
ALTER TABLE `bals`
  ADD CONSTRAINT `bals_ibfk_1` FOREIGN KEY (`corps_id`) REFERENCES `bals_corps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bals_ibfk_2` FOREIGN KEY (`perso_src_id`) REFERENCES `persos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bals_ibfk_3` FOREIGN KEY (`perso_dest_id`) REFERENCES `persos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `bals_listes`
--
ALTER TABLE `bals_listes`
  ADD CONSTRAINT `bals_listes_ibfk_1` FOREIGN KEY (`camp`) REFERENCES `camps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bals_listes_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `persos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `bals_send`
--
ALTER TABLE `bals_send`
  ADD CONSTRAINT `bals_send_ibfk_1` FOREIGN KEY (`perso_src_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `blocks`
--
ALTER TABLE `blocks`
  ADD CONSTRAINT `blocks_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `camps`
--
ALTER TABLE `camps`
  ADD CONSTRAINT `camps_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs`
--
ALTER TABLE `caracs`
  ADD CONSTRAINT `caracs_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs_alter`
--
ALTER TABLE `caracs_alter`
  ADD CONSTRAINT `caracs_alter_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs_alter_affi`
--
ALTER TABLE `caracs_alter_affi`
  ADD CONSTRAINT `caracs_alter_affi_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs_alter_artefact`
--
ALTER TABLE `caracs_alter_artefact`
  ADD CONSTRAINT `caracs_alter_artefact_ibfk_1` FOREIGN KEY (`case_artefact_id`) REFERENCES `case_artefact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs_alter_camp`
--
ALTER TABLE `caracs_alter_camp`
  ADD CONSTRAINT `caracs_alter_camp_ibfk_1` FOREIGN KEY (`camp_id`) REFERENCES `races` (`race_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracs_alter_mag`
--
ALTER TABLE `caracs_alter_mag`
  ADD CONSTRAINT `caracs_alter_mag_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `case_artefact`
--
ALTER TABLE `case_artefact`
  ADD CONSTRAINT `case_artefact_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_artefact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `case_objet_complexe`
--
ALTER TABLE `case_objet_complexe`
  ADD CONSTRAINT `case_objet_complexe_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_objet_complexe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `case_objet_simple`
--
ALTER TABLE `case_objet_simple`
  ADD CONSTRAINT `case_objet_simple_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_objet_simple` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `case_terrain`
--
ALTER TABLE `case_terrain`
  ADD CONSTRAINT `case_terrain_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_terrain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `damier_artefact`
--
ALTER TABLE `damier_artefact`
  ADD CONSTRAINT `damier_artefact_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `damier_bouclier`
--
ALTER TABLE `damier_bouclier`
  ADD CONSTRAINT `damier_bouclier_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `damier_bouclier_ibfk_2` FOREIGN KEY (`objet_lie`) REFERENCES `damier_objet_complexe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `damier_objet_complexe`
--
ALTER TABLE `damier_objet_complexe`
  ADD CONSTRAINT `damier_objet_complexe_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `damier_objet_complexe_ibfk_2` FOREIGN KEY (`case_objet_complexe_id`) REFERENCES `case_objet_complexe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `damier_spawn`
--
ALTER TABLE `damier_spawn`
  ADD CONSTRAINT `damier_spawn_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `damier_terrain`
--
ALTER TABLE `damier_terrain`
  ADD CONSTRAINT `damier_terrain_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `cartes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `damier_terrain_ibfk_2` FOREIGN KEY (`terrain_id`) REFERENCES `case_terrain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `factions`
--
ALTER TABLE `factions`
  ADD CONSTRAINT `factions_ibfk_1` FOREIGN KEY (`race`) REFERENCES `camps` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `grimoire`
--
ALTER TABLE `grimoire`
  ADD CONSTRAINT `grimoire_ibfk_1` FOREIGN KEY (`id_perso`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grimoire_ibfk_2` FOREIGN KEY (`id_sort`) REFERENCES `action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `icone_persos`
--
ALTER TABLE `icone_persos`
  ADD CONSTRAINT `icone_persos_ibfk_1` FOREIGN KEY (`sexe_id`) REFERENCES `sexe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `inventaire_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventaire_ibfk_2` FOREIGN KEY (`case_artefact_id`) REFERENCES `case_artefact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `medailles`
--
ALTER TABLE `medailles`
  ADD CONSTRAINT `medailles_ibfk_1` FOREIGN KEY (`id_perso`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medailles_ibfk_2` FOREIGN KEY (`id_medaille`) REFERENCES `medailles_liste` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `persos`
--
ALTER TABLE `persos`
  ADD CONSTRAINT `persos_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `persos_ibfk_5` FOREIGN KEY (`superieur_id`) REFERENCES `persos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `persos_ibfk_6` FOREIGN KEY (`sexe`) REFERENCES `sexe` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `persos_familier`
--
ALTER TABLE `persos_familier`
  ADD CONSTRAINT `persos_familier_ibfk_1` FOREIGN KEY (`persos_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `races`
--
ALTER TABLE `races`
  ADD CONSTRAINT `races_ibfk_1` FOREIGN KEY (`camp_id`) REFERENCES `camps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `record`
--
ALTER TABLE `record`
  ADD CONSTRAINT `record_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `repertoire`
--
ALTER TABLE `repertoire`
  ADD CONSTRAINT `repertoire_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `repertoire_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs_ban`
--
ALTER TABLE `utilisateurs_ban`
  ADD CONSTRAINT `utilisateurs_ban_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs_ticket`
--
ALTER TABLE `utilisateurs_ticket`
  ADD CONSTRAINT `utilisateurs_ticket_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs_vacances`
--
ALTER TABLE `utilisateurs_vacances`
  ADD CONSTRAINT `utilisateurs_vacances_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `wait_affil`
--
ALTER TABLE `wait_affil`
  ADD CONSTRAINT `wait_affil_ibfk_1` FOREIGN KEY (`superieur`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wait_affil_ibfk_2` FOREIGN KEY (`vassal`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `wait_faction`
--
ALTER TABLE `wait_faction`
  ADD CONSTRAINT `wait_faction_ibfk_1` FOREIGN KEY (`faction_id`) REFERENCES `factions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wait_faction_ibfk_2` FOREIGN KEY (`perso_id`) REFERENCES `persos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
