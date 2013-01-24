DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}` (
  `parametre_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(25) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `est_date` tinyint(1) NOT NULL,
  `valeur_date` date DEFAULT NULL,
  `est_texte` tinyint(1) NOT NULL,
  `valeur_texte` varchar(255) DEFAULT NULL,
  `est_numerique` tinyint(1) NOT NULL,
  `nombre_decimale` int(11) DEFAULT NULL,
  `valeur_numerique` double DEFAULT NULL,
  `version_1.L.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`parametre_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;