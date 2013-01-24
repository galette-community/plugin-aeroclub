ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    CHANGE `id_adherent` 
    `id_adherent` INT( 10 ) UNSIGNED NULL ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    CHANGE `nom` 
    `nom` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    CHANGE `email_contact` 
    `email_contact` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    CHANGE `no_portable` 
    `no_portable` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    CHANGE `version_0.7` 
    `version_0.7.3` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
