ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` ADD `id_instructeur` INT UNSIGNED NULL DEFAULT NULL AFTER `id_avion` ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`
  ADD CONSTRAINT `FK_pilote_reservation_3` FOREIGN KEY (`id_instructeur`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteInstructeur::TABLE}` (`instructeur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` CHANGE `version_1.M.5` `version_1.N.5` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
