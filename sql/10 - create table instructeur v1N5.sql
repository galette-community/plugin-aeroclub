DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}` (
  `instructeur_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `code_adherent` varchar(10) DEFAULT NULL,
  `adherent_id` int(10) unsigned DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_1.N.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`instructeur_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}`
  ADD CONSTRAINT `FK_pilote_adherent_instructeur_1` FOREIGN KEY (`adherent_id`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;
