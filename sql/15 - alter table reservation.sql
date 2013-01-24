ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteReservation::TABLE}` 
    ADD `est_reservation_club` BOOLEAN NOT NULL DEFAULT '0' 
    AFTER `commentaires` ;
