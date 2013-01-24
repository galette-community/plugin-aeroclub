DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}pictures`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}pictures` (
  `avion_id` int(11) NOT NULL,
  `picture` mediumblob NOT NULL,
  `format` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `version_1.M.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`avion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
