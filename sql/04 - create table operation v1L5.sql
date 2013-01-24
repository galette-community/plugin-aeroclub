DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` (
  `operation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `access_exercice` smallint(5) unsigned NOT NULL,
  `access_id` int(10) unsigned NOT NULL,
  `date_operation` date DEFAULT NULL,
  `immatriculation` varchar(10) DEFAULT NULL,
  `duree_minute` int(10) unsigned DEFAULT NULL,
  `type_vol` varchar(40) DEFAULT NULL,
  `aeroport_depart` varchar(10) DEFAULT NULL,
  `aeroport_arrivee` varchar(10) DEFAULT NULL,
  `instructeur` varchar(10) DEFAULT NULL,
  `nb_atterrissages` int(10) unsigned DEFAULT NULL,
  `type_operation` varchar(40) NOT NULL,
  `libelle_operation` varchar(100) NOT NULL,
  `montant_operation` double NOT NULL,
  `sens` varchar(1) NOT NULL,
  `nb_passagers` smallint(6) DEFAULT NULL,
  `indicateur_forfait` tinyint(1) NOT NULL,
  `code_forfait` varchar(10) DEFAULT NULL,
  `version_1.L.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`operation_id`),
  KEY `id_adherent` (`id_adherent`),
  KEY `access_exercice` (`access_exercice`),
  KEY `access_id` (`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}`
  ADD CONSTRAINT `FK_pilote_operations_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;
