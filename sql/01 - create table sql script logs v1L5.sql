DROP TABLE IF EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteSQLScripts::TABLE}`;
CREATE TABLE IF NOT EXISTS `{PREFIX_DB}{PILOTE_PREFIX}{PiloteSQLScripts::TABLE}` (
  `sql_scripts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sql_script` varchar(50) NOT NULL,
  `date_execution` datetime NOT NULL,
  `version_1.L.5` char(1) DEFAULT NULL,
  PRIMARY KEY (`sql_scripts_id`),
  KEY `sql_script` (`sql_script`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;