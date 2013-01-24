
DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}` (
  `complement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `tel_travail` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `no_fax` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `est_eleve` tinyint(1) NOT NULL,
  `indicateur_journalier` int(11) NOT NULL,
  `date_dernier_vol` date DEFAULT NULL,
  `date_visite_medicale` date DEFAULT NULL,
  `date_fin_license` date DEFAULT NULL,
  `date_vol_controle` date DEFAULT NULL,
  `indicateur_forfait` int(11) NOT NULL,
  `ck_situation_aero` tinyint(1) NOT NULL,
  `no_bb` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_ppl` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `releve_mail` tinyint(1) NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`complement_id`),
  KEY `id_adherent` (`id_adherent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (
  `avion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom_court` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `marque_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `type_aeronef` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `immatriculation` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `couleur` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nb_places` int(11) DEFAULT NULL,
  `cout_horaire` double DEFAULT NULL,
  `remarques` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DC_autorisee` tinyint(1) NOT NULL,
  `est_remorqueur` tinyint(1) NOT NULL,
  `est_reservable` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`avion_id`),
  UNIQUE KEY `immatriculation` (`immatriculation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}` (
  `instructeur_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `code_adherent` varchar(10) DEFAULT NULL,
  `adherent_id` int(10) unsigned DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (`instructeur_id`),
  KEY `code` (`code`),
  KEY `FK_pilote_adherent_instructeur_1` (`adherent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` (
  `operation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `access_exercice` smallint(5) unsigned NOT NULL,
  `access_id` int(10) unsigned NOT NULL,
  `date_operation` date DEFAULT NULL,
  `immatriculation` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duree_minute` int(10) unsigned DEFAULT NULL,
  `type_vol` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aeroport_depart` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aeroport_arrivee` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instructeur` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nb_atterrissages` int(10) unsigned DEFAULT NULL,
  `type_operation` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `libelle_operation` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `montant_operation` decimal(15,2) NOT NULL,
  `sens` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `nb_passagers` smallint(6) DEFAULT NULL,
  `indicateur_forfait` tinyint(1) NOT NULL,
  `code_forfait` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`operation_id`),
  KEY `id_adherent` (`id_adherent`),
  KEY `access_exercice` (`access_exercice`),
  KEY `access_id` (`access_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}` (
  `parametre_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `libelle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `est_date` tinyint(1) NOT NULL,
  `valeur_date` date DEFAULT NULL,
  `est_texte` tinyint(1) NOT NULL,
  `valeur_texte` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `est_numerique` tinyint(1) NOT NULL,
  `nombre_decimale` int(11) DEFAULT NULL,
  `valeur_numerique` double DEFAULT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`parametre_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}pictures`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}pictures` (
  `avion_id` int(11) NOT NULL,
  `picture` mediumblob NOT NULL,
  `format` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`avion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` (
  `reservation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `id_avion` int(10) unsigned NOT NULL,
  `id_instructeur` int(10) unsigned DEFAULT NULL,
  `heure_debut` datetime NOT NULL,
  `heure_fin` datetime NOT NULL,
  `nom` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email_contact` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `no_portable` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `commentaires` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `est_reservation_club` tinyint(1) NOT NULL DEFAULT '0',
  `est_rapproche` tinyint(1) NOT NULL DEFAULT '0',
  `id_operation` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `id_adherent` (`id_adherent`),
  KEY `id_avion` (`id_avion`),
  KEY `FK_pilote_reservation_3` (`id_instructeur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteSQLScripts::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteSQLScripts::TABLE}` (
  `sql_scripts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sql_script` varchar(50) NOT NULL,
  `date_execution` datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (`sql_scripts_id`),
  KEY `sql_script` (`sql_script`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;


DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}` (
  `dispo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `avion_id` int(10) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (`dispo_id`),
  KEY `avion_id` (`avion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}`
  ADD CONSTRAINT `FK_pilote_avion_1` FOREIGN KEY (`avion_id`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (`avion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}`
  ADD CONSTRAINT `FK_pilote_adherent_complement_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}`
  ADD CONSTRAINT `FK_pilote_adherent_instructeur_1` FOREIGN KEY (`adherent_id`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}`
  ADD CONSTRAINT `FK_pilote_operations_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`
  ADD CONSTRAINT `FK_pilote_reservation_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_pilote_reservation_2` FOREIGN KEY (`id_avion`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (`avion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_pilote_reservation_3` FOREIGN KEY (`id_instructeur`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}` (`instructeur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_pilote_reservation_4` FOREIGN KEY (`id_operation`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` (`operation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

