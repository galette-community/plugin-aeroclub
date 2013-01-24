DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}` (
  `complement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_adherent` int(10) unsigned NOT NULL,
  `tel_travail` varchar(25) NOT NULL,
  `no_fax` varchar(25) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `est_eleve` tinyint(1) NOT NULL,
  `indicateur_journalier` int(11) NOT NULL,
  `date_dernier_vol` date DEFAULT NULL,
  `date_visite_medicale` date DEFAULT NULL,
  `date_fin_license` date DEFAULT NULL,
  `date_vol_controle` date DEFAULT NULL,
  `indicateur_forfait` int(11) NOT NULL,
  `ck_situation_aero` tinyint(1) NOT NULL,
  `no_bb` varchar(20) DEFAULT NULL,
  `no_ppl` varchar(20) DEFAULT NULL,
  `releve_mail` tinyint(1) NOT NULL,
  `version_1.L.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`complement_id`),
  KEY `id_adherent` (`id_adherent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}`
  ADD CONSTRAINT `FK_pilote_adherent_complement_1` FOREIGN KEY (`id_adherent`) REFERENCES `{PREFIX_DB}{Adherent::TABLE}` (`id_adh`) ON DELETE NO ACTION ON UPDATE NO ACTION;
