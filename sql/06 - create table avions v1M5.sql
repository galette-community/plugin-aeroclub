DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (
  `avion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `immatriculation` varchar(10) NOT NULL,
  `couleur` varchar(30) DEFAULT NULL,
  `nb_places` int(11) DEFAULT NULL,
  `cout_horaire` double DEFAULT NULL,
  `remarques` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_1.M.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`avion_id`),
  UNIQUE KEY `immatriculation` (`immatriculation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
