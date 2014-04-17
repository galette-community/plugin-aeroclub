<?php

/**
 * i18n
 *
 * PHP version 5
 *
 * Copyright © 2011 Mélissa Djebel
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Plugin Pilote is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugins
 * @package   GalettePilote
 *
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   0.7
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
/**
 * MENU.TPL
 */
$lang['MENU.TITRE SECTION'] = 'Pilot';
$lang['MENU.TITRE SECTION ADMIN'] = 'Pilot admin';
$lang['MENU.TITRE SECTION GESTION'] = 'Managment';
$lang['MENU.COMPTE'] = 'My operations';
$lang['MENU.RESERVATION'] = 'Book an aircraft';
$lang['MENU.PLANNING'] = 'Books calendar';
$lang['MENU.LISTE VOLS'] = 'My flights';
$lang['MENU.GRAPHIQUE'] = 'My graphic';
$lang['MENU.FICHE PILOTE'] = 'My pilot card';
$lang['MENU.SITUATION AERO'] = 'My aeronautical position';
$lang['MENU.MOT DE PASSE'] = 'My password';
$lang['MENU.DOCUMENTS 1'] = 'Documents';
$lang['MENU STAFF.HEURES AVION'] = 'Aircraft flight hours';
$lang['MENU STAFF.HEURES PILOTE'] = 'Pilot flight hours';
$lang['MENU STAFF.SOLDES PILOTES'] = 'Pilots balance';
$lang['MENU STAFF.TYPES VOLS PILOTES'] = 'Pilot flight types';
$lang['MENU ADMIN.LISTE AVIONS'] = 'Aircrafts';
$lang['MENU ADMIN.LISTE INSTRUCTEURS'] = 'Instructors';
$lang['MENU ADMIN.NOUVELLE OPERATION'] = 'New operation';
$lang['MENU ADMIN.RAPPROCHEMENT'] = 'Books reconciliation';
$lang['MENU ADMIN.CHANGER MOT PASSE'] = 'Change password';
$lang['MENU ADMIN.SQL'] = 'SQL tables managment';
$lang['MENU ADMIN.IMPORT'] = 'Access 2 import';
$lang['MENU ADMIN.HISTO IMPORT'] = 'Import history';
$lang['MENU ADMIN.PARAMETRES'] = 'Flying club parameters';
$lang['MENU ADMIN.VERSION'] = 'Changelog';

/**
 * IMPORT.TPL
 */
$lang['IMPORT.PAGE TITLE'] = 'Import a CSV file from Access';
$lang['IMPORT.TITRE FICHIER'] = 'Import a from from Access';
$lang['IMPORT.CHOIX FICHIER'] = 'Choose the file:';
$lang['IMPORT.FILTRER IMPORT'] = 'Filter on update date:';
$lang['IMPORT.DATE DERNIER IMPORT'] = '- checked, only imports data that has been updated after (≥) ';
$lang['IMPORT.DATE IMPORT COMPLEMENT'] = '- unchecked, imports all data by possibly overwriting ';
$lang['IMPORT.IGNORE SECTION'] = 'Import even if the section is not ';
$lang['IMPORT.CHOIX ANNEE IMPORT'] = 'Choose the imported year:';
$lang['IMPORT.DONNEES ANNEES SUPPRIMEES'] = 'all data from this year will be removed';
$lang['IMPORT.ENVOYER'] = 'Import the file';
$lang['IMPORT.NOM'] = 'Name';
$lang['IMPORT.TAILLE'] = 'Size';
$lang['IMPORT.NB ADH'] = 'Members n#';
$lang['IMPORT.NB OPE'] = 'Operation n#';
$lang['IMPORT.LIGNES TRAITEES'] = 'Processed rows';
$lang['IMPORT.LIGNES TOTAL'] = 'Total rows';
$lang['IMPORT.IMPORTE LE'] = 'Imported on';
$lang['IMPORT.EXPORTE LE'] = 'Exported at';
$lang['IMPORT.TYPE'] = 'Type';
$lang['IMPORT.REUSSI'] = 'Import succeeded';
$lang['IMPORT.LIGNES LUES'] = 'line(s) read from the export';
$lang['IMPORT.LIGNES IMPORTEES'] = 'row(s) updated';
$lang['IMPORT.TABLE SQL CREEE'] = 'The SQL table did not exist and was created';
$lang['IMPORT.PAS ANNEE'] = ' --- None --- ';
$lang['IMPORT.SUPPRIMER'] = 'Delete checked files';
$lang['IMPORT.FICHIERS SUPPRIMES'] = 'file(s) deleted';
$lang['IMPORT.CHECK'] = 'Check all';
$lang['IMPORT.UNCHECK'] = 'Uncheck all';
$lang['IMPORT.INVERT'] = 'Invert selected';

/**
 * HISTO_IMPORT.TPL 
 */
$lang['HISTO IMPORT.PAGE TITLE'] = 'Import history';

/**
 * COMPTE_VOL.TPL
 */
$lang['COMPTE VOL.PAGE TITLE'] = 'Querying a member account';
$lang['COMPTE VOL.LISTE ANNEES'] = 'Available years for viewing';
$lang['COMPTE VOL.ANNEE'] = 'Select the year:';
$lang['COMPTE VOL.TOUTES'] = '--- All ---';
$lang['COMPTE VOL.AFFICHER'] = 'Display';
$lang['COMPTE VOL.IMPRIMER'] = 'View as PDF';
$lang['COMPTE VOL.ADHERENT'] = 'Pilot:';
$lang['COMPTE VOL.NB LIGNES'] = 'Rows per page: ';
$lang['COMPTE VOL.RESULTATS'] = 'operations';
$lang['COMPTE VOL.DATE'] = 'Date';
$lang['COMPTE VOL.TYPE'] = 'Type';
$lang['COMPTE VOL.LIBELLE'] = 'Label';
$lang['COMPTE VOL.DUREE'] = 'Duration';
$lang['COMPTE VOL.MONTANT'] = 'Amount';
$lang['COMPTE VOL.TOTAUX'] = 'Total';
$lang['COMPTE VOL.TOTAL VOL'] = 'Flight duration total';
$lang['COMPTE VOL.SOLDE'] = 'Balance at ';
$lang['COMPTE VOL.ENREGISTRE OK'] = 'Operation has been updated';
$lang['COMPTE VOL.PAS ENREGISTRE'] = 'Operation modification has been canceled';

$lang['COMPTE VOL.MOIS.01'] = 'January';
$lang['COMPTE VOL.MOIS.02'] = 'February';
$lang['COMPTE VOL.MOIS.03'] = 'March';
$lang['COMPTE VOL.MOIS.04'] = 'April';
$lang['COMPTE VOL.MOIS.05'] = 'May';
$lang['COMPTE VOL.MOIS.06'] = 'June';
$lang['COMPTE VOL.MOIS.07'] = 'July';
$lang['COMPTE VOL.MOIS.08'] = 'August';
$lang['COMPTE VOL.MOIS.09'] = 'September';
$lang['COMPTE VOL.MOIS.10'] = 'October';
$lang['COMPTE VOL.MOIS.11'] = 'November';
$lang['COMPTE VOL.MOIS.12'] = 'December';

/**
 * LISTE_VOLS.TPL
 */
$lang['LISTE VOLS.PAGE TITLE'] = 'Member flights';
$lang['LISTE VOLS.LISTE ANNEES'] = 'Available years for viewing';
$lang['LISTE VOLS.ANNEE'] = 'Select the year:';
$lang['LISTE VOLS.TOUTES'] = '--- All ---';
$lang['LISTE VOLS.AFFICHER'] = 'Display';
$lang['LISTE VOLS.ADHERENT'] = 'Pilot:';
$lang['LISTE VOLS.RESULTATS'] = 'flights';
$lang['LISTE VOLS.DATE'] = 'Date';
$lang['LISTE VOLS.IMMAT'] = 'Aircraft';
$lang['LISTE VOLS.TYPE VOL'] = 'Flight type';
$lang['LISTE VOLS.AEROPORTS'] = 'Departure / Arrival';
$lang['LISTE VOLS.PASSAGERS'] = 'Passengers';
$lang['LISTE VOLS.INSTRUCTEUR'] = 'Instructor';
$lang['LISTE VOLS.NB ATTERRISSAGES'] = 'Landing';
$lang['LISTE VOLS.DUREE'] = 'Duration';
$lang['LISTE VOLS.MONTANT'] = 'Amount';
$lang['LISTE VOLS.ENREGISTRE OK'] = 'Operation has been updated';
$lang['LISTE VOLS.PAS ENREGISTRE'] = 'Operation modification has been canceled';

/**
 * FICHE_PILOTE.TPL
 */
$lang['FICHE PILOTE.PAGE TITLE'] = 'Pilot card';
$lang['FICHE PILOTE.TABLE TITLE'] = 'See general information of a member';
$lang['FICHE PILOTE.ADHERENT'] = 'Member:';
$lang['FICHE PILOTE.AFFICHER'] = 'Display';
$lang['FICHE PILOTE.FICHE'] = 'General informations';
$lang['FICHE PILOTE.CODE'] = 'Code:';
$lang['FICHE PILOTE.NOM'] = 'Name:';
$lang['FICHE PILOTE.PRENOM'] = 'First name:';
$lang['FICHE PILOTE.DATE NAISSANCE'] = 'Birth date:';
$lang['FICHE PILOTE.ADRESSE'] = 'Address:';
$lang['FICHE PILOTE.CP'] = 'Zip code:';
$lang['FICHE PILOTE.VILLE'] = 'Town:';
$lang['FICHE PILOTE.EMAIL'] = 'eMail:';
$lang['FICHE PILOTE.TELEPHONES'] = 'Phone numbers';
$lang['FICHE PILOTE.TELEPHONE'] = 'Home:';
$lang['FICHE PILOTE.PORTABLE'] = 'Mobile:';
$lang['FICHE PILOTE.TEL TRAVAIL'] = 'Work:';
$lang['FICHE PILOTE.SITUATION AERO'] = 'Aeronautical position';
$lang['FICHE PILOTE.VISITE MEDICALE'] = 'Medical examination end date:';
$lang['FICHE PILOTE.VOL CONTROLE'] = 'Flight control end date:';
$lang['FICHE PILOTE.DERNIER VOL'] = 'Last flight:';
$lang['FICHE PILOTE.FIN LICENSE'] = 'License ends:';
$lang['FICHE PILOTE.NO BB'] = 'BB #:';
$lang['FICHE PILOTE.NO PPL'] = 'PPL #:';
$lang['FICHE PILOTE.AUTRE QUALIF'] = 'Other qualifications:';
$lang['FICHE PILOTE.ELEVE'] = 'Pilot student?';

/**
 * SITUATION_AERO.TPL
 */
$lang['SITUATION AERO.PAGE TITLE'] = 'Pilot aeronautical position';
$lang['SITUATION AERO.TABLE TITLE'] = 'View member aeronautical position';
$lang['SITUATION AERO.ADHERENT'] = 'Member:';
$lang['SITUATION AERO.AFFICHER'] = 'Display';
$lang['SITUATION AERO.SITUATION'] = 'Aeronautical position';
$lang['SITUATION AERO.SOLDE'] = 'Balance:';
$lang['SITUATION AERO.TEMPS VOL'] = 'Flight time this year:';
$lang['SITUATION AERO.TEMPS VOL GLISSANT'] = 'Flight time last 12 months:';
$lang['SITUATION AERO.VALIDITE LICENSE'] = 'License end date:';
$lang['SITUATION AERO.VISITE MEDICALE'] = 'Medical examination end date:';
$lang['SITUATION AERO.VOL CONTROLE'] = 'Flight control end date:';
$lang['SITUATION AERO.NB ATTERISSAGES'] = 'Landing last 3 months:';
$lang['SITUATION AERO.DERNIER VOL'] = 'Last flight:';
$lang['SITUATION AERO.WARNING'] = 'Caution: this information does not include flights elsewhere';

/**
 * PARAMETRES.TPL
 */
$lang['PARAMETRES.PAGE TITLE'] = 'Flying club parameters';
$lang['PARAMETRES.LISTE'] = 'Parameters';
$lang['PARAMETRES.TITRE ACCESS'] = 'Access';
$lang['PARAMETRES.TITRE WEB'] = 'Pilot galette';
$lang['PARAMETRES.LEGENDE ACCESS'] = 'Access-parameters (do not touch):';
$lang['PARAMETRES.LEGENDE WEB'] = 'Parameters used in the plugin Galette Pilot:';
$lang['PARAMETRES.CODE'] = 'Code';
$lang['PARAMETRES.FORMAT'] = 'Data type';
$lang['PARAMETRES.CHOIX'] = '-- Select --';
$lang['PARAMETRES.DATE'] = 'Date';
$lang['PARAMETRES.TEXTE'] = 'Text';
$lang['PARAMETRES.NUMERIQUE'] = 'Numeric';
$lang['PARAMETRES.VALEUR'] = 'Value';
$lang['PARAMETRES.ENREGISTRER'] = 'Save';
$lang['PARAMETRES.PARAMETRES SAUVES'] = 'Parameters new values have been saved.';

/**
 * SQL.TPL
 */
$lang['SQL.PAGE TITLE'] = 'Update SQL data tables';
$lang['SQL.EXISTENCE TABLES'] = 'Flying club data table exists';
$lang['SQL.TABLE ADHERENT COMPLEMENT'] = 'Members complements';
$lang['SQL.TABLE OPERATION'] = 'Operations (contributions / flights)';
$lang['SQL.TABLE PARAMETRE'] = 'Parameters';
$lang['SQL.TABLE SQL'] = 'SQL scripts histories';
$lang['SQL.EXISTE'] = 'Ok';
$lang['SQL.EXISTE PAS'] = '/!\\ Do not exists !!!';
$lang['SQL.ENREGISTREMENTS'] = 'rows';
$lang['SQL.DESCRIPTION TABLE'] = 'Table columns:';
$lang['SQL.SUR LANCER SCRIPT'] = 'Are you sure you want to run this SQL script: ';
$lang['SQL.JOUER SCRIPT'] = 'Run a SQL script on database';
$lang['SQL.NOM SCRIPT'] = 'Script name';
$lang['SQL.DATE MODIF'] = 'Modification date';
$lang['SQL.NB EXEC'] = 'Executing #';
$lang['SQL.DERNIERE EXEC'] = 'Last executed date';
$lang['SQL.ACTION'] = 'Action';
$lang['SQL.LANCER'] = 'Run !';

/**
 * LISTE AVIONS.TPL
 */
$lang['LISTE AVIONS.PAGE TITLE'] = 'Flying club aircrafts';
$lang['LISTE AVIONS.RESULTATS'] = 'aircrafts';
$lang['LISTE AVIONS.NOM'] = 'Aircraft';
$lang['LISTE AVIONS.NOM COURT'] = 'Short name';
$lang['LISTE AVIONS.MARQUE TYPE'] = 'Brand &amp; type';
$lang['LISTE AVIONS.TYPE AERONEF'] = 'Aircraft type';
$lang['LISTE AVIONS.IMMAT'] = 'Plate';
$lang['LISTE AVIONS.COULEUR'] = 'Color';
$lang['LISTE AVIONS.NB PLACES'] = 'Seats';
$lang['LISTE AVIONS.DC'] = 'DC?';
$lang['LISTE AVIONS.REMORQUEUR'] = 'Tug?';
$lang['LISTE AVIONS.DATES'] = 'Not available booking';
$lang['LISTE AVIONS.RESERVABLE'] = 'Booking?';
$lang['LISTE AVIONS.COUT'] = 'Hourly cost';
$lang['LISTE AVIONS.AJOUTER'] = 'Add a new aircraft';
$lang['LISTE AVIONS.ENREGISTRE OK'] = 'Modifications have been saved';
$lang['LISTE AVIONS.PAS ENREGISTRE'] = 'Modifications have been canceled';
$lang['LISTE AVIONS.SUPPRIME'] = 'Aircraft has been deleted';
$lang['LISTE AVIONS.CONFIRM SUPPR'] = 'Are you sure you want to delete this aircraft: ';

/**
 * MODIFIER AVION.TPL
 */
$lang['MODIFIER AVION.PAGE TITLE'] = 'Add / edit an aircraft';
$lang['MODIFIER AVION.TITRE'] = 'Aircraft definition';
$lang['MODIFIER AVION.NOM'] = 'Name:';
$lang['MODIFIER AVION.NOM COURT'] = 'Short name:';
$lang['MODIFIER AVION.MARQUE TYPE'] = 'Brand &amp; type :';
$lang['MODIFIER AVION.TYPE AERONEF'] = 'Aircraft type:';
$lang['MODIFIER AVION.IMMAT'] = 'Plate:';
$lang['MODIFIER AVION.COULEUR'] = 'Color:';
$lang['MODIFIER AVION.PLACES'] = 'Seats:';
$lang['MODIFIER AVION.COUT'] = 'Hourly cost:';
$lang['MODIFIER AVION.REMQS'] = 'Comments:';
$lang['MODIFIER AVION.DC'] = 'DC allowed:';
$lang['MODIFIER AVION.REMORQUEUR'] = 'Can tow?';
$lang['MODIFIER AVION.RESERVABLE'] = 'Can be booked?';
$lang['MODIFIER AVION.PHOTO'] = 'Aircraft picture:';
$lang['MODIFIER AVION.SUPPR PHOTO'] = 'Remove actual picture';
$lang['MODIFIER AVION.MINIATURE'] = 'Automatic thumb:';
$lang['MODIFIER AVION.ENREGISTRER'] = 'Save';
$lang['MODIFIER AVION.ANNULER'] = 'Cancel';

/**
 * DISPO.TPL
 */
$lang['DISPO.ELEMENTS SAUVES'] = ' available bookings date updated';
$lang['DISPO.PAGE TITLE'] = 'Aircraft unavailable dates';
$lang['DISPO.AVION'] = 'Aircraft';
$lang['DISPO.CAPTION'] = 'Periods when the aircraft is not bookable';
$lang['DISPO.DEBUT'] = 'Begin date';
$lang['DISPO.FIN'] = 'End date (can be empty)';
$lang['DISPO.AJOUTER'] = 'To add a period, simply complete this row';
$lang['DISPO.SUPPRIMER'] = 'Delete';
$lang['DISPO.SUPPRIMER LIGNE'] = 'Delete row';
$lang['DISPO.EXPLICATION'] = 'How it works?' .
        '<br/>The aircraft will not be available on the specified period.' .
        '<br/>In order for the aircraft to be always available, do not fill a row.';
$lang['DISPO.VALIDER'] = 'Save';
$lang['DISPO.RETOUR'] = 'Back to aircrafts';

/**
 * LISTE INSTRUCTEURS.TPL
 */
$lang['LISTE INSTRUCTEURS.PAGE TITLE'] = 'Instructors';
$lang['LISTE INSTRUCTEURS.CODE'] = 'Code';
$lang['LISTE INSTRUCTEURS.NOM'] = 'Name';
$lang['LISTE INSTRUCTEURS.CODE ADHERENT'] = 'Pilot';
$lang['LISTE INSTRUCTEURS.AJOUTER'] = 'Add an instructor';
$lang['LISTE INSTRUCTEURS.RESULTATS'] = 'instructors';
$lang['LISTE INSTRUCTEURS.CONFIRM SUPPR'] = 'Are you sure you want to delete this instructor: ';
$lang['LISTE INSTRUCTEURS.PAS ENREGISTRE'] = 'Modifications have been saved';
$lang['LISTE INSTRUCTEURS.ENREGISTRE OK'] = 'Modifications have been canceled';
$lang['LISTE INSTRUCTEURS.SUPPRIME'] = 'Instructor has been deleted';

/**
 * MODIFIER INSTRUCTEUR.TPL
 */
$lang['MODIFIER INSTRUCTEUR.PAGE TITLE'] = 'Add / edit an instructor';
$lang['MODIFIER INSTRUCTEUR.TITRE'] = 'Instructor informations';
$lang['MODIFIER INSTRUCTEUR.CODE'] = 'Code:';
$lang['MODIFIER INSTRUCTEUR.NOM'] = 'Name:';
$lang['MODIFIER INSTRUCTEUR.ADHERENT'] = 'Associated member:';
$lang['MODIFIER INSTRUCTEUR.CODE ADHERENT'] = 'Member code:';
$lang['MODIFIER INSTRUCTEUR.EXTERNE'] = '--- External ---';
$lang['MODIFIER INSTRUCTEUR.ENREGISTRER'] = 'Save';
$lang['MODIFIER INSTRUCTEUR.ANNULER'] = 'Cancel';

/**
 * RESERVATION.TPL
 */
$lang['RESERVATION.PAGE TITLE'] = 'Book an aircraft';
$lang['RESERVATION.RESA OK'] = 'Your booking has been saved';
$lang['RESERVATION.RESA ANNULE'] = 'Booking has been canceld';
$lang['RESERVATION.RESA SUPPRIME'] = 'Booking has been deleted';
$lang['RESERVATION.DUPLICATION'] = 'This booking will be duplicated and saved as a new booking, the original remains unmodified';
$lang['RESERVATION.CHOIX'] = 'Select the aircraft';
$lang['RESERVATION.SEMAINE'] = 'Week';
$lang['RESERVATION.ALLER'] = 'Go to';
$lang['RESERVATION.CHOISIR'] = 'Choose the';
$lang['RESERVATION.SEM PREC'] = 'Previous week';
$lang['RESERVATION.SEM SUIV'] = 'Next week';
$lang['RESERVATION.RESERVER LE'] = 'Book the';
$lang['RESERVATION.A PARTIR'] = 'from';
$lang['RESERVATION.MODIFIER'] = 'Edit this booking';
$lang['RESERVATION.COPIER'] = 'Add a new book with the information from this one';
$lang['RESERVATION.RESERVER'] = 'Book';
$lang['RESERVATION.ANNULER'] = 'Cancel';
$lang['RESERVATION.SAUVER'] = 'Edit';
$lang['RESERVATION.CLONER'] = 'Clone';
$lang['RESERVATION.SUPPRIMER'] = 'Delete';
$lang['RESERVATION.CONFIRM SUPPRESSION'] = 'Are you sure you want to delete this booking?';
$lang['RESERVATION.DETAIL RESA'] = 'Booking details';
$lang['RESERVATION.LEGENDE'] = 'Legend';
$lang['RESERVATION.PAS RESA'] = 'No booking';
$lang['RESERVATION.NUIT AERO'] = '(aeronautical night)';
$lang['RESERVATION.LIBRE'] = 'Free slot';
$lang['RESERVATION.LIBRE DEPASSE'] = 'Free slot but date expired';
$lang['RESERVATION.RESERVE'] = 'Booked';
$lang['RESERVATION.RESA AVION'] = 'Aircraft:';
$lang['RESERVATION.RESA JOUR'] = 'Flight day:';
$lang['RESERVATION.RESA HEURE'] = 'From:';
$lang['RESERVATION.RESA ADH'] = 'Member:';
$lang['RESERVATION.RESA INSTR'] = 'Instructor:';
$lang['RESERVATION.RESA DUREE'] = 'Till:';
$lang['RESERVATION.TOUTE LA JOURNEE'] = 'Full day:';
$lang['RESERVATION.JOURNEE COMPLETE'] = 'Check this box to book a full day';
$lang['RESERVATION.RESA NOM'] = 'Name:';
$lang['RESERVATION.RESA DESTI'] = 'Destination:';
$lang['RESERVATION.RESA EMAIL'] = 'Email:';
$lang['RESERVATION.RESA PORT'] = 'Mobile phone:';
$lang['RESERVATION.RESA COMMENTS'] = 'Comments:';
$lang['RESERVATION.RESA RESA'] = 'Flying club book:';
$lang['RESERVATION.TITLE RESA CLUB LABEL'] = 'Aircraft booking by flying club';
$lang['RESERVATION.TITLE ADHERENT'] = 'Select the member that is booking this aircraft.';
$lang['RESERVATION.CHOIX ADHERENT'] = '--- Select a member ---';
$lang['RESERVATION.TITLE INSTRUC'] = 'If you are student, select your the flying instructor.';
$lang['RESERVATION.CHOIX INSTRUC'] = '--- None / No instructor ---';
$lang['RESERVATION.TITLE RESA JOUR'] = 'Select the flying day';
$lang['RESERVATION.TITLE HEURE DEBUT'] = 'Select the booking hour begin';
$lang['RESERVATION.TITLE DUREE'] = 'Select the booking hour end. <br/>The treasurer will validate the real invoiced duration.';
$lang['RESERVATION.SOIT'] = 'that is';
$lang['RESERVATION.TITLE NOM'] = 'Fill in your name. <br/><b>Mandatory</b>.';
$lang['RESERVATION.TITLE DESTI'] = 'Fill in the flight destination.';
$lang['RESERVATION.TITLE EMAIL'] = 'Fill in the email address where we can write you. <br/><b>Mandatory</b>.';
$lang['RESERVATION.TITLE PORTABLE'] = 'Fill in the mobile phone number where we can reach you. <br/><b>Mandatory</b>.';
$lang['RESERVATION.TITLE COMMENTS'] = 'Fill in any comments that you consider relevant to your booking.';
$lang['RESERVATION.TITLE RESA CLUB'] = 'Checked: whether there is a booking for the flying club (open doors, etc.). <br/>Unchecked: private booking.';

$lang['RESERVATION.MOIS.01'] = 'Jan';
$lang['RESERVATION.MOIS.02'] = 'Feb';
$lang['RESERVATION.MOIS.03'] = 'March';
$lang['RESERVATION.MOIS.04'] = 'Apr';
$lang['RESERVATION.MOIS.05'] = 'May';
$lang['RESERVATION.MOIS.06'] = 'June';
$lang['RESERVATION.MOIS.07'] = 'July';
$lang['RESERVATION.MOIS.08'] = 'Aug';
$lang['RESERVATION.MOIS.09'] = 'Sep';
$lang['RESERVATION.MOIS.10'] = 'Oct';
$lang['RESERVATION.MOIS.11'] = 'Nov';
$lang['RESERVATION.MOIS.12'] = 'Déc';

$lang['RESERVATION.JOUR.0'] = 'Sunday';
$lang['RESERVATION.JOUR.1'] = 'Monday';
$lang['RESERVATION.JOUR.2'] = 'Tuesday';
$lang['RESERVATION.JOUR.3'] = 'Wednesday';
$lang['RESERVATION.JOUR.4'] = 'Thursday';
$lang['RESERVATION.JOUR.5'] = 'Friday';
$lang['RESERVATION.JOUR.6'] = 'Saturday';

/**
 * PLANNING.TPL
 */
$lang['PLANNING.PAGE TITLE'] = 'Books calendar';
$lang['PLANNING.AVIONS'] = 'Aircrafts';
$lang['PLANNING.SEMAINE'] = 'Week';
$lang['PLANNING.ALLER'] = 'Go to';
$lang['PLANNING.RESERVER'] = 'Add a booking';

$lang['PLANNING.JOUR.0'] = 'Su';
$lang['PLANNING.JOUR.1'] = 'Mo';
$lang['PLANNING.JOUR.2'] = 'Tu';
$lang['PLANNING.JOUR.3'] = 'We';
$lang['PLANNING.JOUR.4'] = 'Th';
$lang['PLANNING.JOUR.5'] = 'Fr';
$lang['PLANNING.JOUR.6'] = 'Sa';

/**
 * NEW OPERATION.TPL
 */
$lang['NEW OPERATION.PAGE TITLE'] = 'Add an operation';
$lang['NEW OPERATION.ENREGISTRE OK'] = 'Operation has been added';
$lang['NEW OPERATION.PAS ENREGISTRE'] = 'Operation has been canceled';
$lang['NEW OPERATION.TITRE'] = 'General informations about the operation';
$lang['NEW OPERATION.ADHERENT'] = 'Pilot:';
$lang['NEW OPERATION.TYPE OPERATON'] = 'Operation type:';
$lang['NEW OPERATION.AUTRE'] = '--- Other : fill-in =>';
$lang['NEW OPERATION.OU AUTRE'] = 'or other:';
$lang['NEW OPERATION.LIBELLE OPERATION'] = 'Operation label:';
$lang['NEW OPERATION.EXERCICE'] = 'Accountant year:';
$lang['NEW OPERATION.DATE'] = 'Operation date:';
$lang['NEW OPERATION.MONTANT'] = 'Amount:';
$lang['NEW OPERATION.VOL'] = 'Details if it\'s a flight';
$lang['NEW OPERATION.AVION'] = 'Aircraft:';
$lang['NEW OPERATION.TYPE VOL'] = 'Flight type:';
$lang['NEW OPERTATION.AEROPORT'] = 'Destination:';
$lang['NEW OPERTATION.DEPART'] = 'Departure:';
$lang['NEW OPERTATION.ARRIVEE'] = 'Arrival:';
$lang['NEW OPERATION.PASSAGERS'] = 'Passengers n#:';
$lang['NEW OPERATION.INSTRUCTEUR'] = 'Instructor:';
$lang['NEW OPERATION.ATTERISSAGE'] = 'Landing n#:';
$lang['NEW OPERATION.DUREE'] = 'Flight duration:';
$lang['NEW OPERATION.ENREGISTRER'] = 'Save operation';
$lang['NEW OPERATION.ANNULER'] = 'Cancel';

/**
 * RAPPROCHEMENT.TPL
 */
$lang['RAPPROCHEMENT.PAGE TITLE'] = 'Booking reconciliation';
$lang['RAPPROCHEMENT.ACTION'] = 'Action';
$lang['RAPPROCHEMENT.RESERVATION'] = 'Booking';
$lang['RAPPROCHEMENT.OPERATION'] = 'Operation';
$lang['RAPPROCHEMENT.RAPPROCHER'] = 'Reconciliate';
$lang['RAPPROCHEMENT.IMPUTER'] = 'Charge to:';
$lang['RAPPROCHEMENT.AVION'] = 'Aircraft:';
$lang['RAPPROCHEMENT.TYPE VOL'] = 'Flight type:';
$lang['RAPPROCHEMENT.DEP ARR'] = 'Departure / Arrival:';
$lang['RAPPROCHEMENT.MONTANT'] = 'Amount:';
$lang['RAPPROCHEMENT.LIBELLE'] = 'Label:';
$lang['RAPPROCHEMENT.INSTRUCTEUR'] = 'Instructor:';
$lang['RAPPROCHEMENT.ATTERRISSAGES'] = 'Landing n#:';
$lang['RAPPROCHEMENT.PASSAGERS'] = 'Passengers n#:';
$lang['RAPPROCHEMENT.DUREE'] = 'Duration:';
$lang['RAPPROCHEMENT.AUTRE'] = '--- Or other ---';
$lang['RAPPROCHEMENT.OU'] = 'Or';
$lang['RAPPROCHEMENT.OPERATION OK'] = 'operations generated with success';
$lang['RAPPROCHEMENT.ENREGISTRER'] = 'Confirm reconciliation';

/**
 * GRAPHIQUE.TPL 
 */
$lang['GRAPHIQUE.PAGE TITLE'] = 'Graphic flight time and account balance';
$lang['GRAPHIQUE.TABLE TITLE'] = 'View graphic for member';
$lang['GRAPHIQUE.ADHERENT'] = 'Member:';
$lang['GRAPHIQUE.AFFICHER'] = 'Display';
$lang['GRAPHIQUE.IMAGE'] = 'Evolution of my flight times per month and cumulative balance of my flying club account';
$lang['GRAPHIQUE.LEGENDE'] = 'Legend';
$lang['GRAPHIQUE.BLEU1'] = 'Blue bar';
$lang['GRAPHIQUE.BLEU2'] = 'Flight time per month';
$lang['GRAPHIQUE.VIOLET1'] = 'Purple line';
$lang['GRAPHIQUE.VIOLET2'] = 'Flight time sum over the year';
$lang['GRAPHIQUE.ORANGE1'] = 'Orange line';
$lang['GRAPHIQUE.ORANGE2'] = 'Balance sum over the year';

/**
 * HEURES.TPL 
 */
$lang['HEURES PILOTE.PAGE TITLE'] = 'Members flying hours';
$lang['HEURES PILOTE.TABLEAU'] = 'List';
$lang['HEURES PILOTE.GRAPHIQUE'] = 'Graphic';
$lang['HEURES PILOTE.TABLEAU LEGENDE'] = 'Pilots flying hours (list)';
$lang['HEURES PILOTE.GRAPHIQUE LEGENDE'] = 'Pilots flying hours (graphic)';
$lang['HEURES AVION.PAGE TITLE'] = 'Aircraft flying hours';
$lang['HEURES AVION.TABLEAU'] = 'List';
$lang['HEURES AVION.MOIS12'] = '12 last month';
$lang['HEURES AVION.TABLEAU LEGENDE'] = 'Aircraft flying hours (list)';
$lang['HEURES AVION.MOIS12 LEGENDE'] = 'Aircraft flying hours (graphic)';
$lang['HEURES AVION.ANNEES'] = 'By year';
$lang['HEURES AVION.ANNEES LEGENDE'] = 'Aircraft flying hours by year (graphic)';
$lang['HEURES AVION.CHOIX IMMAT'] = 'Select the plate to display:';
$lang['HEURES AVION.CHOIX ANNEES'] = 'Select the years to display (3 max):';
$lang['HEURES.ADHERENT NOM'] = 'Name';
$lang['HEURES.ADHERENT PRENOM'] = 'First name';
$lang['HEURES.ADHERENT PSEUDO'] = 'Identifier';
$lang['HEURES.ANNEE DERNIERE'] = 'Last year';
$lang['HEURES.CETTE ANNEE'] = 'This year';
$lang['HEURES.UN AN GLISSANT'] = '12 last month<br/>since';
$lang['HEURES.AVION'] = 'Aircraft';

/**
 * TYPES VOLS PILOTES
 */
$lang['TYPES VOLS PILOTES.PAGE TITLE'] = 'Pilots flying time by types';

/**
 * SOLDES PILOTES.TPL 
 */
$lang['SOLDES PILOTES.PAGE TITLE'] = 'Pilot balance';
$lang['SOLDES PILOTES.NOM'] = 'Name';
$lang['SOLDES PILOTES.PRENOM'] = 'First name';
$lang['SOLDES PILOTES.PSEUDO'] = 'Id.';
$lang['SOLDES PILOTES.EMAIL'] = 'E-mail';
$lang['SOLDES PILOTES.SOLDE'] = 'Balance';
$lang['SOLDES PILOTES.ACTIF'] = 'Show only active pilotes:';
$lang['SOLDES PILOTES.SOLDE NEGATIF'] = 'Display balance:';
$lang['SOLDES PILOTES.OUI'] = 'Yes';
$lang['SOLDES PILOTES.NON'] = 'No';
$lang['SOLDES PILOTES.NEGATIF'] = 'negative';
$lang['SOLDES PILOTES.ZERO'] = 'equal 0';
$lang['SOLDES PILOTES.POSITIF'] = 'positive';
$lang['SOLDES PILOTES.TOUS'] = 'All';
$lang['SOLDES PILOTES.ENVOYER MAILING'] = 'Send a mailing';

/**
 * VERSION.TPL 
 */
$lang['VERSION.PAGE TITLE'] = 'Changelod';

/**
 * DOCUMENTS_FRAME.TPL
 */
$lang['DOCUMENTS IFRAME.PAGE TITLE'] = 'Flying club documents';

/**
 * PiloteAdherentComplement.class.php
 */
$lang['COMPLEMENT.AJOUT SUCCES'] = 'Nouvelle fiche de complément pilote ajoutée';
$lang['COMPLEMENT.AJOUT ECHEC'] = 'Echec lors de l\'ajout d\'une fiche de complément pilote';
$lang['COMPLEMENT.MISE A JOUR'] = 'La fiche de complément existante a été mise à jour';

/**
 * PiloteOperation.class.php
 */
$lang['OPERATION.AJOUT SUCCES'] = 'L\'opération du pilote a été ajoutée correctement';
$lang['OPERATION.AJOUT ECHEC'] = 'Une erreur est survenue lors de l\'ajout de l\'opération du pilote';
$lang['OPERATION.MISE A JOUR'] = 'L\'opération du pilote a été mise à jour';

$lang['AJAX.CLOSE'] = 'Close';
