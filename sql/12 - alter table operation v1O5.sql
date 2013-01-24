ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` CHANGE `montant_operation` `montant_operation` DECIMAL( 15, 2 ) NOT NULL ;

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` CHANGE `version_1.M.5` `version_1.O.5` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
