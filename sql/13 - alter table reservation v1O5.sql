ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` ADD `est_rapproche` BOOLEAN NOT NULL DEFAULT '0' AFTER `commentaires` ,
ADD `id_operation` INT NULL DEFAULT NULL AFTER `est_rapproche` ;

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` CHANGE `version_1.N.5` `version_1.O.5` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}`
  ADD CONSTRAINT `FK_operation_4` FOREIGN KEY (`id_operation`) REFERENCES `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` (`operation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
