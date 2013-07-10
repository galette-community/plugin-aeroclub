
DROP TABLE IF EXISTS galette_pilote_adherent_complement;
CREATE TABLE IF NOT EXISTS galette_pilote_adherent_complement (
  complement_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_adherent int(10) unsigned NOT NULL,
  tel_travail varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  no_fax varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  actif tinyint(1) NOT NULL,
  est_eleve tinyint(1) NOT NULL,
  indicateur_journalier int(11) NOT NULL,
  date_dernier_vol date DEFAULT NULL,
  date_visite_medicale date DEFAULT NULL,
  date_fin_license date DEFAULT NULL,
  date_vol_controle date DEFAULT NULL,
  indicateur_forfait int(11) NOT NULL,
  ck_situation_aero tinyint(1) NOT NULL,
  no_bb varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  no_ppl varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  releve_mail tinyint(1) NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  date_creation datetime DEFAULT NULL,
  date_modification datetime DEFAULT NULL,
  PRIMARY KEY (complement_id),
  KEY id_adherent (id_adherent)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS galette_pilote_avions;
CREATE TABLE IF NOT EXISTS galette_pilote_avions (
  avion_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  nom varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  nom_court varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  marque_type varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  type_aeronef varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  immatriculation varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  couleur varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  nb_places int(11) DEFAULT NULL,
  cout_horaire double DEFAULT NULL,
  remarques varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  DC_autorisee tinyint(1) NOT NULL,
  est_remorqueur tinyint(1) NOT NULL,
  est_reservable tinyint(1) NOT NULL,
  actif tinyint(1) NOT NULL DEFAULT '1',
  date_creation datetime NOT NULL,
  date_modification datetime NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (avion_id),
  UNIQUE KEY immatriculation (immatriculation)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS galette_pilote_instructeurs;
CREATE TABLE IF NOT EXISTS galette_pilote_instructeurs (
  instructeur_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(5) NOT NULL,
  nom varchar(40) NOT NULL,
  code_adherent varchar(10) DEFAULT NULL,
  adherent_id int(10) unsigned DEFAULT NULL,
  date_creation datetime NOT NULL,
  date_modification datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (instructeur_id),
  KEY code (code),
  KEY FK_pilote_adherent_instructeur_1 (adherent_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;


DROP TABLE IF EXISTS galette_pilote_operations;
CREATE TABLE IF NOT EXISTS galette_pilote_operations (
  operation_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_adherent int(10) unsigned NOT NULL,
  access_exercice smallint(5) unsigned NOT NULL,
  access_id int(10) unsigned NOT NULL,
  date_operation date DEFAULT NULL,
  immatriculation varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  duree_minute int(10) unsigned DEFAULT NULL,
  type_vol varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  aeroport_depart varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  aeroport_arrivee varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  instructeur varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  nb_atterrissages int(10) unsigned DEFAULT NULL,
  type_operation varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  libelle_operation varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  montant_operation decimal(15,2) NOT NULL,
  sens varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  nb_passagers smallint(6) DEFAULT NULL,
  indicateur_forfait tinyint(1) NOT NULL,
  code_forfait varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  date_creation datetime DEFAULT NULL,
  date_modification datetime DEFAULT NULL,
  PRIMARY KEY (operation_id),
  KEY id_adherent (id_adherent),
  KEY access_exercice (access_exercice),
  KEY access_id (access_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS galette_pilote_parametres;
CREATE TABLE IF NOT EXISTS galette_pilote_parametres (
  parametre_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  libelle varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  est_date tinyint(1) NOT NULL,
  valeur_date date DEFAULT NULL,
  est_texte tinyint(1) NOT NULL,
  valeur_texte varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  est_numerique tinyint(1) NOT NULL,
  nombre_decimale int(11) DEFAULT NULL,
  valeur_numerique double DEFAULT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  date_creation datetime DEFAULT NULL,
  date_modification datetime DEFAULT NULL,
  PRIMARY KEY (parametre_id),
  UNIQUE KEY code (code)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS galette_pilote_pictures;
CREATE TABLE IF NOT EXISTS galette_pilote_pictures (
  avion_id int(11) NOT NULL,
  picture mediumblob NOT NULL,
  format varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (avion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS galette_pilote_reservations;
CREATE TABLE IF NOT EXISTS galette_pilote_reservations (
  reservation_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_adherent int(10) unsigned DEFAULT NULL,
  id_avion int(10) unsigned NOT NULL,
  id_instructeur int(10) unsigned DEFAULT NULL,
  heure_debut datetime NOT NULL,
  heure_fin datetime NOT NULL,
  nom varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  destination varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  email_contact varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  no_portable varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  commentaires varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  est_reservation_club tinyint(1) NOT NULL DEFAULT '0',
  est_rapproche tinyint(1) NOT NULL DEFAULT '0',
  id_operation int(10) unsigned DEFAULT NULL,
  date_creation datetime NOT NULL,
  date_modification datetime NOT NULL,
  `version_0.7` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (reservation_id),
  KEY id_adherent (id_adherent),
  KEY id_avion (id_avion),
  KEY FK_pilote_reservation_3 (id_instructeur)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;


DROP TABLE IF EXISTS galette_pilote_sql_scripts;
CREATE TABLE IF NOT EXISTS galette_pilote_sql_scripts (
  sql_scripts_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sql_script varchar(50) NOT NULL,
  date_execution datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (sql_scripts_id),
  KEY sql_script (sql_script)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;


DROP TABLE IF EXISTS galette_pilote_avions_dispo;
CREATE TABLE IF NOT EXISTS galette_pilote_avions_dispo (
  dispo_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  avion_id int(10) unsigned NOT NULL,
  date_debut date NOT NULL,
  date_fin date DEFAULT NULL,
  date_creation datetime NOT NULL,
  date_modification datetime NOT NULL,
  `version_0.7` char(1) DEFAULT NULL,
  PRIMARY KEY (dispo_id),
  KEY avion_id (avion_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


ALTER TABLE galette_pilote_avions_dispo
  ADD CONSTRAINT FK_pilote_avion_1 FOREIGN KEY (avion_id) REFERENCES galette_pilote_avions (avion_id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE galette_pilote_adherent_complement
  ADD CONSTRAINT FK_pilote_adherent_complement_1 FOREIGN KEY (id_adherent) REFERENCES galette_adherents (id_adh) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE galette_pilote_instructeurs
  ADD CONSTRAINT FK_pilote_adherent_instructeur_1 FOREIGN KEY (adherent_id) REFERENCES galette_adherents (id_adh) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE galette_pilote_operations
  ADD CONSTRAINT FK_pilote_operations_1 FOREIGN KEY (id_adherent) REFERENCES galette_adherents (id_adh) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE galette_pilote_operations ADD INDEX (immatriculation) ;


ALTER TABLE galette_pilote_reservations
  ADD CONSTRAINT FK_pilote_reservation_1 FOREIGN KEY (id_adherent) REFERENCES galette_adherents (id_adh) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT FK_pilote_reservation_2 FOREIGN KEY (id_avion) REFERENCES galette_pilote_avions (avion_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT FK_pilote_reservation_3 FOREIGN KEY (id_instructeur) REFERENCES galette_pilote_instructeurs (instructeur_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT FK_pilote_reservation_4 FOREIGN KEY (id_operation) REFERENCES galette_pilote_operations (operation_id) ON DELETE NO ACTION ON UPDATE NO ACTION;

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('DATE_DERNIER_IMPORT', 'Indique la date de dernier import d\'Access vers Galette', 1, '1980-01-01', 0, null, 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_LIBRE', 'Couleur de fond des cellules où l\'on peut réserver un avion', 0, null, 1, '#00FF7F', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_RESERVE', 'Couleur de fond des cellules lorsqu\'un avion est réservé par un membre', 0, null, 1, '#D8BFD8', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COULEUR_INTERDIT', 'Couleur de fond des cellules lorsqu\'il est interdit de réserver un avion', 0, null, 1, '#FF6347', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_DEBUT', 'Première heure réservable dans la journée', 0, null, 0, null, 1, 0, 7, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_FIN', 'Dernière heure réservable dans la journée', 0, null, 0, null, 1, 0, 21, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_SAMEDI', 'Heure à partir de laquelle, on ne peut plus réserver le samedi (école)', 0, null, 0, null, 1, 0, 14, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('CALENDRIER_HEURE_DIMANCHE', 'Heure à partir de laquelle, on ne peut plus réserver le dimanche (école)', 0, null, 0, null, 1, 0, 14, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AUTORISER_RESA_INTERDIT', 'Autoriser la réservation sur les pages marquées interdites (valeur: O/N)', 0, null, 1, 'O', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AEROCLUB_LATITUDE', 'Latitude de l\'aéroclub (pour le calcul de nuit aéro)', 0, null, 0, null, 1, 4, 45.6777, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AEROCLUB_LONGITUDE', 'Longitude de l\'aéroclub (pour le calcul de nuit aéro)', 0, null, 0, null, 1, 4, 5.4687, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('AD_DEFAUT', 'Code OACI de l\'Aerodrome de l\'Aeroclub.', 0, null, 1, 'LFHI', 0, null, null, NOW(), NOW());

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('COTISATION_SECTION', 'Nom de la cotisation qui rend le membre actif', 0, null, 1, 'ACM', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_RESERVATION', 'Bloquer résa aux pilotes pas en règle (solde, situation aéro), valeurs D/W/B (off/warning/bloquer)', 0, null, 1, 'B', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_MESSAGE_WARNING', 'Message de warning si blocage est en mode Warning', 0, null, 1, 'Attention votre solde est négatif. Votre réservation peut être annulée par le trésorier. Mettez vous en règle!', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('BLOCAGE_MESSAGE_BLOQUE', 'Message de blocage si blocage est en mode Blocage (empêche réservation)', 0, null, 1, 'La date de validitée de votre license ou visite médicale est dépassée. Vous ne pouvez pas effectuer de réservation.', 0, null, null, NOW(), NOW()); 

INSERT IGNORE INTO galette_pilote_parametres
(code, libelle, est_date, valeur_date, est_texte, valeur_texte, est_numerique, nombre_decimale, valeur_numerique, date_creation, date_modification)
VALUES ('INSTRUCTEUR_RESA', 'Indique si les instructeurs peuvent modifier toutes les réservations (0 = non, 1 = oui)', 0, null, 0, null, 1, 0, 0, NOW(), NOW()); 
