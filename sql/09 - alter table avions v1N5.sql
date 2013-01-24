ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` ADD `nom_court` VARCHAR( 20 ) NOT NULL AFTER `nom` ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` CHANGE `type` `marque_type` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` ADD `type_aeronef` VARCHAR( 30 ) NOT NULL AFTER `marque_type` ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` ADD `DC_autorisee` BOOLEAN NOT NULL AFTER `remarques` ,
ADD `est_remorqueur` BOOLEAN NOT NULL AFTER `DC_autorisee` ,
ADD `est_reservable` BOOLEAN NOT NULL AFTER `est_remorqueur` ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAvion::TABLE}` CHANGE `version_1.M.5` `version_1.N.5` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
