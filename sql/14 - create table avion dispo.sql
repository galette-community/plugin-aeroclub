DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}` (
  `dispo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `avion_id` int(10) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_1.O.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`dispo_id`),
  KEY `avion_id` (`avion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvionDispo::TABLE}`
  ADD CONSTRAINT `FK_pilote_avion_1` FOREIGN KEY (`avion_id`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (`avion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
