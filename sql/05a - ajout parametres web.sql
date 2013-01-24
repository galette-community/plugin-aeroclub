INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('DATE_DERNIER_IMPORT', 'Indique la date de dernier import d\'Access vers Galette', 1, '1980-01-01', 0, null, 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_LIBRE', 'Couleur de fond des cellules où l\'on peut réserver un avion', 0, null, 1, '#00FF7F', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_RESERVE', 'Couleur de fond des cellules lorsqu\'un avion est réservé par un membre', 0, null, 1, '#D8BFD8', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_INTERDIT', 'Couleur de fond des cellules lorsqu\'il est interdit de réserver un avion', 0, null, 1, '#FF6347', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_DEBUT', 'Première heure réservable dans la journée', 0, null, 0, null, 1, 0, 7, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_FIN', 'Dernière heure réservable dans la journée', 0, null, 0, null, 1, 0, 21, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_SAMEDI', 'Heure à partir de laquelle, on ne peut plus réserver le samedi (école)', 0, null, 0, null, 1, 0, 14, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_DIMANCHE', 'Heure à partir de laquelle, on ne peut plus réserver le dimanche (école)', 0, null, 0, null, 1, 0, 14, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AUTORISER_RESA_INTERDIT', 'Autoriser la réservation sur les pages marquées interdites (valeur: O/N)', 0, null, 1, 'O', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AEROCLUB_LATITUDE', 'Latitude de l\'aéroclub (pour le calcul de nuit aéro)', 0, null, 0, null, 1, 4, 45.6777, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AEROCLUB_LONGITUDE', 'Longitude de l\'aéroclub (pour le calcul de nuit aéro)', 0, null, 0, null, 1, 4, 5.4687, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AD_DEFAUT', 'Code OACI de l\'Aerodrome de l\'Aeroclub.', 0, null, 1, 'LFHI', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COTISATION_SECTION', 'Nom de la cotisation qui rend le membre actif', 0, null, 1, 'ACM', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_RESERVATION', 'Bloquer résa aux pilotes pas en règle (solde, situation aéro), valeurs D/W/B (off/warning/bloquer)', 0, null, 1, 'B', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_MESSAGE_WARNING', 'Message de warning si blocage est en mode Warning', 0, null, 1, 'Attention votre solde est négatif. Votre réservation peut être annulée par le trésorier. Mettez vous en règle!', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_MESSAGE_BLOQUE', 'Message de blocage si blocage est en mode Blocage (empêche réservation)', 0, null, 1, 'La date de validitée de votre license ou visite médicale est dépassée. Vous ne pouvez pas effectuer de réservation.', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO {PREFIX_DB}{PILOTE_PREFIX}{PiloteParametre::TABLE}
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('INSTRUCTEUR_RESA', 'Indique si les instructeurs peuvent modifier toutes les réservations (0 = non, 1 = oui)', 0, null, 0, null, 1, 0, 0, NOW(), NOW()); 
