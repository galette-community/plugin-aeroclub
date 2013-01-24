DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` (
  `reservation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `id_avion` int(10) unsigned NOT NULL,
  `heure_debut` datetime NOT NULL,
  `heure_fin` datetime NOT NULL,
  `nom` varchar(60) NOT NULL,
  `destination` varchar(30) NOT NULL,
  `email_contact` varchar(30) NOT NULL,
  `no_portable` varchar(20) NOT NULL,
  `commentaires` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `version_1.M.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `id_adherent` (`id_adherent`),
  KEY `id_avion` (`id_avion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`
  ADD CONSTRAINT `FK_pilote_reservation_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`
  ADD CONSTRAINT `FK_pilote_reservation_2` FOREIGN KEY (`id_avion`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` (`avion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
