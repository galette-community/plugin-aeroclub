INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('INSTRUCTEUR_RESA', 'Indique si les instructeurs peuvent modifier toutes les réservations (0 = non, 1 = oui)', 0, null, 0, null, 1, 0, 0, NOW(), NOW()); 
