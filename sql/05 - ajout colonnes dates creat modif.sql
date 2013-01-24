ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}` DROP `version_1.L.5`;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}` 
ADD `version_1.M.5` CHAR(1) DEFAULT NULL,
ADD `date_creation` DATETIME NULL DEFAULT NULL,
ADD `date_modification` DATETIME NULL DEFAULT NULL;
UPDATE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteAdherentComplement::TABLE}` 
SET `date_creation` = NOW(), `date_modification` = NOW();

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` DROP `version_1.L.5`;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` 
ADD `version_1.M.5` CHAR(1) DEFAULT NULL,
ADD `date_creation` DATETIME NULL DEFAULT NULL,
ADD `date_modification` DATETIME NULL DEFAULT NULL;
UPDATE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteOperation::TABLE}` 
SET `date_creation` = NOW(), `date_modification` = NOW();

ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}` DROP `version_1.L.5`;
ALTER TABLE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}` 
ADD `version_1.M.5` CHAR(1) DEFAULT NULL,
ADD `date_creation` DATETIME NULL DEFAULT NULL,
ADD `date_modification` DATETIME NULL DEFAULT NULL;
UPDATE `{PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}` 
SET `date_creation` = NOW(), `date_modification` = NOW();

