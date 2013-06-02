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
$lang['MENU.TITRE SECTION'] = 'Pilote';
$lang['MENU.TITRE SECTION ADMIN'] = 'Admin pilote';
$lang['MENU.TITRE SECTION GESTION'] = 'Gestion';
$lang['MENU.COMPTE'] = 'Mes opérations';
$lang['MENU.RESERVATION'] = 'Réserver un aéronef';
$lang['MENU.PLANNING'] = 'Planning réservations';
$lang['MENU.LISTE VOLS'] = 'Mes vols';
$lang['MENU.GRAPHIQUE'] = 'Mon graphique';
$lang['MENU.FICHE PILOTE'] = 'Ma fiche pilote';
$lang['MENU.SITUATION AERO'] = 'Ma situation aéronautique';
$lang['MENU.MOT DE PASSE'] = 'Mon mot de passe';
$lang['MENU.DOCUMENTS 1']='Documents (iframe)';
$lang['MENU.DOCUMENTS 2']='Documents (fullscreen)';
$lang['MENU STAFF.HEURES AVION'] = 'Heures de vol aéronefs';
$lang['MENU STAFF.HEURES PILOTE'] = 'Heures de vol pilotes';
$lang['MENU STAFF.SOLDES PILOTES'] = 'Soldes pilotes';
$lang['MENU STAFF.TYPES VOLS PILOTES'] = 'Types vols pilotes';
$lang['MENU ADMIN.LISTE AVIONS'] = 'Liste aéronefs';
$lang['MENU ADMIN.LISTE INSTRUCTEURS'] = 'Liste instructeurs';
$lang['MENU ADMIN.NOUVELLE OPERATION'] = 'Nouvelle opération';
$lang['MENU ADMIN.RAPPROCHEMENT'] = 'Rapprochement résas';
$lang['MENU ADMIN.CHANGER MOT PASSE'] = 'Changer un mot de passe';
$lang['MENU ADMIN.SQL'] = 'Gestion tables SQL';
$lang['MENU ADMIN.IMPORT'] = 'Import Access 2';
$lang['MENU ADMIN.HISTO IMPORT'] = 'Historique import';
$lang['MENU ADMIN.PARAMETRES'] = 'Paramètres aéroclub';
$lang['MENU ADMIN.VERSION'] = 'Notes de versions';

/**
 * IMPORT.TPL
 */
$lang['IMPORT.PAGE TITLE'] = 'Importer un fichier CSV depuis Access';
$lang['IMPORT.TITRE FICHIER'] = 'Importer un fichier depuis Access';
$lang['IMPORT.CHOIX FICHIER'] = 'Sélectionner le fichier :';
$lang['IMPORT.FILTRER IMPORT'] = 'Filtrer sur date de mise à jour :';
$lang['IMPORT.DATE DERNIER IMPORT'] = '- coché, importe seulement les données mise à jour à partir du (&ge;) ';
$lang['IMPORT.DATE IMPORT COMPLEMENT'] = '- décoché, importe toutes les données en les écrasant éventuellement';
$lang['IMPORT.IGNORE SECTION'] = 'Importer même si la section n\'est pas ';
$lang['IMPORT.CHOIX ANNEE IMPORT'] = 'Sélectionner l\'année importée :';
$lang['IMPORT.DONNEES ANNEES SUPPRIMEES'] = 'toutes les données de cette année seront supprimées';
$lang['IMPORT.ENVOYER'] = 'Envoyer';
$lang['IMPORT.REUSSI'] = 'Import réussi';
$lang['IMPORT.LIGNES LUES'] = 'ligne(s) lues dans l\'export';
$lang['IMPORT.LIGNES IMPORTEES'] = 'ligne(s) mises à jour en base';
$lang['IMPORT.TABLE SQL CREEE'] = 'La table SQL n\'existait pas et a été créée';
$lang['IMPORT.PAS ANNEE'] = ' --- Aucune --- ';
$lang['IMPORT.SUPPRIMER'] = 'Supprimer les fichiers cochés';
$lang['IMPORT.FICHIERS SUPPRIMES'] = 'fichier(s) supprimé(s)';

/**
 * HISTO_IMPORT.TPL 
 */
$lang['HISTO IMPORT.PAGE TITLE'] = 'Historique des imports';

/**
 * COMPTE_VOL.TPL
 */
$lang['COMPTE VOL.PAGE TITLE'] = 'Interrogation compte d\'un membre';
$lang['COMPTE VOL.LISTE ANNEES'] = 'Années disponibles à l\'affichage';
$lang['COMPTE VOL.ANNEE'] = 'Choix de l\'année :';
$lang['COMPTE VOL.TOUTES'] = '--- Toutes ---';
$lang['COMPTE VOL.AFFICHER'] = 'Afficher';
$lang['COMPTE VOL.IMPRIMER'] = 'Version PDF';
$lang['COMPTE VOL.ADHERENT'] = 'Voir pilote :';
$lang['COMPTE VOL.NB LIGNES'] = 'Nombre d\'enregistrements par page : ';
$lang['COMPTE VOL.RESULTATS'] = 'opérations';
$lang['COMPTE VOL.DATE'] = 'Date';
$lang['COMPTE VOL.TYPE'] = 'Type';
$lang['COMPTE VOL.LIBELLE'] = 'Libellé';
$lang['COMPTE VOL.DUREE'] = 'Durée';
$lang['COMPTE VOL.MONTANT'] = 'Montant';
$lang['COMPTE VOL.TOTAUX'] = 'Totaux';
$lang['COMPTE VOL.TOTAL VOL'] = 'Total durée vols';
$lang['COMPTE VOL.SOLDE'] = 'Solde au ';
$lang['COMPTE VOL.ENREGISTRE OK'] = 'L\'opération a été mise à jour';
$lang['COMPTE VOL.PAS ENREGISTRE'] = 'L\'opération n\'a pas été modifiée';

$lang['COMPTE VOL.MOIS.01'] = 'Janvier';
$lang['COMPTE VOL.MOIS.02'] = 'Février';
$lang['COMPTE VOL.MOIS.03'] = 'Mars';
$lang['COMPTE VOL.MOIS.04'] = 'Avril';
$lang['COMPTE VOL.MOIS.05'] = 'Mai';
$lang['COMPTE VOL.MOIS.06'] = 'Juin';
$lang['COMPTE VOL.MOIS.07'] = 'Juillet';
$lang['COMPTE VOL.MOIS.08'] = 'Août';
$lang['COMPTE VOL.MOIS.09'] = 'Septembre';
$lang['COMPTE VOL.MOIS.10'] = 'Octobre';
$lang['COMPTE VOL.MOIS.11'] = 'Novembre';
$lang['COMPTE VOL.MOIS.12'] = 'Décembre';

/**
 * LISTE_VOLS.TPL
 */
$lang['LISTE VOLS.PAGE TITLE'] = 'Vols d\'un membre';
$lang['LISTE VOLS.LISTE ANNEES'] = 'Années disponibles à l\'affichage';
$lang['LISTE VOLS.ANNEE'] = 'Choix de l\'année :';
$lang['LISTE VOLS.TOUTES'] = '--- Toutes ---';
$lang['LISTE VOLS.AFFICHER'] = 'Afficher';
$lang['LISTE VOLS.ADHERENT'] = 'Voir pilote :';
$lang['LISTE VOLS.RESULTATS'] = 'vols';
$lang['LISTE VOLS.DATE'] = 'Date';
$lang['LISTE VOLS.IMMAT'] = 'Aéronef';
$lang['LISTE VOLS.TYPE VOL'] = 'Type vol';
$lang['LISTE VOLS.AEROPORTS'] = 'Départ / Arrivée';
$lang['LISTE VOLS.PASSAGERS'] = 'Passagers';
$lang['LISTE VOLS.INSTRUCTEUR'] = 'Instructeur';
$lang['LISTE VOLS.NB ATTERRISSAGES'] = 'Atterissages';
$lang['LISTE VOLS.DUREE'] = 'Durée';
$lang['LISTE VOLS.MONTANT'] = 'Montant';
$lang['LISTE VOLS.ENREGISTRE OK'] = 'L\'opération a été mise à jour';
$lang['LISTE VOLS.PAS ENREGISTRE'] = 'L\'opération n\'a pas été modifiée';

/**
 * FICHE_PILOTE.TPL
 */
$lang['FICHE PILOTE.PAGE TITLE'] = 'Fiche du pilote';
$lang['FICHE PILOTE.TABLE TITLE'] = 'Voir les informations générales d\'un adhérent';
$lang['FICHE PILOTE.ADHERENT'] = 'Adhérent :';
$lang['FICHE PILOTE.AFFICHER'] = 'Afficher';
$lang['FICHE PILOTE.FICHE'] = 'Informations générales';
$lang['FICHE PILOTE.CODE'] = 'Code :';
$lang['FICHE PILOTE.NOM'] = 'Nom :';
$lang['FICHE PILOTE.PRENOM'] = 'Prénom :';
$lang['FICHE PILOTE.DATE NAISSANCE'] = 'Date de naissance :';
$lang['FICHE PILOTE.ADRESSE'] = 'Adresse :';
$lang['FICHE PILOTE.CP'] = 'Code postal :';
$lang['FICHE PILOTE.VILLE'] = 'Ville :';
$lang['FICHE PILOTE.EMAIL'] = 'eMail :';
$lang['FICHE PILOTE.TELEPHONES'] = 'Téléphones';
$lang['FICHE PILOTE.TELEPHONE'] = 'Domicile :';
$lang['FICHE PILOTE.PORTABLE'] = 'Portable :';
$lang['FICHE PILOTE.TEL TRAVAIL'] = 'Travail :';
$lang['FICHE PILOTE.SITUATION AERO'] = 'Situation aéronautique';
$lang['FICHE PILOTE.VISITE MEDICALE'] = 'Date fin visite médicale :';
$lang['FICHE PILOTE.VOL CONTROLE'] = 'Date fin vol de contrôle :';
$lang['FICHE PILOTE.DERNIER VOL'] = 'Dernier vol le :';
$lang['FICHE PILOTE.FIN LICENSE'] = 'Date fin de license :';
$lang['FICHE PILOTE.NO BB'] = 'N° BB :';
$lang['FICHE PILOTE.NO PPL'] = 'N° PPL :';
$lang['FICHE PILOTE.AUTRE QUALIF'] = 'Autres qualifications :';
$lang['FICHE PILOTE.ELEVE'] = 'Elève pilote ?';

/**
 * SITUATION_AERO.TPL
 */
$lang['SITUATION AERO.PAGE TITLE'] = 'Situation aéronautique du pilote';
$lang['SITUATION AERO.TABLE TITLE'] = 'Voir la situation aéro de l\'adhérent';
$lang['SITUATION AERO.ADHERENT'] = 'Adhérent :';
$lang['SITUATION AERO.AFFICHER'] = 'Afficher';
$lang['SITUATION AERO.SITUATION'] = 'Situation pilote';
$lang['SITUATION AERO.SOLDE'] = 'Solde :';
$lang['SITUATION AERO.TEMPS VOL'] = 'Temps de vol cette année :';
$lang['SITUATION AERO.TEMPS VOL GLISSANT'] = 'Temps de vol sur une année glissante :';
$lang['SITUATION AERO.VALIDITE LICENSE'] = 'Fin de validité de license :';
$lang['SITUATION AERO.VISITE MEDICALE'] = 'Fin de validité visite médicale :';
$lang['SITUATION AERO.VOL CONTROLE'] = 'Fin de validité vol de contrôle :';
$lang['SITUATION AERO.NB ATTERISSAGES'] = 'Nb d\'atterrissages dans les 3 mois :';
$lang['SITUATION AERO.DERNIER VOL'] = 'Date dernier vol :';
$lang['SITUATION AERO.WARNING'] = 'Attention : ces informations ne tiennent pas compte des vols effectués par ailleurs';

/**
 * PARAMETRES.TPL
 */
$lang['PARAMETRES.PAGE TITLE'] = 'Paramètres de l\'Aéroclub';
$lang['PARAMETRES.LISTE'] = 'Liste des paramètres';
$lang['PARAMETRES.TITRE ACCESS'] = 'Access';
$lang['PARAMETRES.TITRE WEB'] = 'Galette Pilote';
$lang['PARAMETRES.LEGENDE ACCESS'] = 'Paramètres utilisés par Access (ne pas modifier) :';
$lang['PARAMETRES.LEGENDE WEB'] = 'Paramètres utilisés dans le plugin Galette Pilote :';
$lang['PARAMETRES.CODE'] = 'Code';
$lang['PARAMETRES.FORMAT'] = 'Type de donnée';
$lang['PARAMETRES.CHOIX'] = '-- Choix --';
$lang['PARAMETRES.DATE'] = 'Date';
$lang['PARAMETRES.TEXTE'] = 'Texte';
$lang['PARAMETRES.NUMERIQUE'] = 'Numérique';
$lang['PARAMETRES.VALEUR'] = 'Valeur';
$lang['PARAMETRES.ENREGISTRER'] = 'Enregistrer';
$lang['PARAMETRES.PARAMETRES SAUVES'] = 'Les nouvelles valeurs de paramètres ont été sauvées.';

/**
 * SQL.TPL
 */
$lang['SQL.PAGE TITLE'] = 'Mettre à jour les tables SQL';
$lang['SQL.EXISTENCE TABLES'] = 'Existence des tables Aéroclub';
$lang['SQL.TABLE ADHERENT COMPLEMENT'] = 'Table de complément des adhérents';
$lang['SQL.TABLE OPERATION'] = 'Table des opérations (cotisations / vols)';
$lang['SQL.TABLE PARAMETRE'] = 'Table des paramètres';
$lang['SQL.TABLE SQL'] = 'Table historique scripts SQL';
$lang['SQL.EXISTE'] = 'Ok';
$lang['SQL.EXISTE PAS'] = '/!\\ N\'existe pas !!!';
$lang['SQL.ENREGISTREMENTS'] = 'ligne(s)';
$lang['SQL.DESCRIPTION TABLE'] = 'Colonnes de la table :';
$lang['SQL.SUR LANCER SCRIPT'] = 'Êtes-vous sur de vouloir lancer ce script SQL : ';
$lang['SQL.JOUER SCRIPT'] = 'Exécuter un script SQL sur le serveur';
$lang['SQL.NOM SCRIPT'] = 'Nom script';
$lang['SQL.DATE MODIF'] = 'Date modification';
$lang['SQL.NB EXEC'] = 'Nb exécution';
$lang['SQL.DERNIERE EXEC'] = 'Dernière exécution';
$lang['SQL.ACTION'] = 'Action';
$lang['SQL.LANCER'] = 'Lancer !';

/**
 * LISTE AVIONS.TPL
 */
$lang['LISTE AVIONS.PAGE TITLE'] = 'Liste des aéronefs de l\'aéroclub';
$lang['LISTE AVIONS.RESULTATS'] = 'aéronefs';
$lang['LISTE AVIONS.NOM'] = 'Aéronef';
$lang['LISTE AVIONS.NOM COURT'] = 'Nom court';
$lang['LISTE AVIONS.MARQUE TYPE'] = 'Marque &amp; type';
$lang['LISTE AVIONS.TYPE AERONEF'] = 'Type aéronef';
$lang['LISTE AVIONS.IMMAT'] = 'Immat.';
$lang['LISTE AVIONS.COULEUR'] = 'Couleur';
$lang['LISTE AVIONS.NB PLACES'] = 'Nb places';
$lang['LISTE AVIONS.DC'] = 'DC?';
$lang['LISTE AVIONS.REMORQUEUR'] = 'Rmoq?';
$lang['LISTE AVIONS.DATES'] = 'Dates résa impossible';
$lang['LISTE AVIONS.RESERVABLE'] = 'Résa?';
$lang['LISTE AVIONS.COUT'] = 'Coût horaire';
$lang['LISTE AVIONS.AJOUTER'] = 'Ajouter un nouvel aéronef';
$lang['LISTE AVIONS.ENREGISTRE OK'] = 'Les modifications ont été enregistrées';
$lang['LISTE AVIONS.PAS ENREGISTRE'] = 'Les modifications ont été annulées';
$lang['LISTE AVIONS.SUPPRIME'] = 'L\'aéronef a été supprimé';
$lang['LISTE AVIONS.CONFIRM SUPPR'] = 'Êtes vous sur de vouloir supprimer cet aéronef : ';

/**
 * MODIFIER AVION.TPL
 */
$lang['MODIFIER AVION.PAGE TITLE'] = 'Ajouter / modifier un aéronef';
$lang['MODIFIER AVION.TITRE'] = 'Définition de l\'aéronef';
$lang['MODIFIER AVION.NOM'] = 'Nom :';
$lang['MODIFIER AVION.NOM COURT'] = 'Nom court :';
$lang['MODIFIER AVION.MARQUE TYPE'] = 'Marque &amp; type :';
$lang['MODIFIER AVION.TYPE AERONEF'] = 'Type aéronef :';
$lang['MODIFIER AVION.IMMAT'] = 'Immatriculation :';
$lang['MODIFIER AVION.COULEUR'] = 'Couleur :';
$lang['MODIFIER AVION.PLACES'] = 'Nombre de places :';
$lang['MODIFIER AVION.COUT'] = 'Coût horaire :';
$lang['MODIFIER AVION.REMQS'] = 'Remarques :';
$lang['MODIFIER AVION.DC'] = 'DC autorisée :';
$lang['MODIFIER AVION.REMORQUEUR'] = 'Peut remorquer ?';
$lang['MODIFIER AVION.RESERVABLE'] = 'Peut être réservé ?';
$lang['MODIFIER AVION.PHOTO'] = 'Photo de l\'aéronef :';
$lang['MODIFIER AVION.SUPPR PHOTO'] = 'Supprimer la photo actuelle';
$lang['MODIFIER AVION.MINIATURE'] = 'Miniature automatique :';
$lang['MODIFIER AVION.ENREGISTRER'] = 'Enregistrer';
$lang['MODIFIER AVION.ANNULER'] = 'Annuler';

/**
 * DISPO.TPL
 */
$lang['DISPO.ELEMENTS SAUVES'] = ' créneau(x) de disponibilité mis à jour';
$lang['DISPO.PAGE TITLE'] = 'Indisponibilités de l\'aéronef';
$lang['DISPO.AVION'] = 'Aéronef';
$lang['DISPO.CAPTION'] = 'Périodes où l\'aéronef ne sera pas réservable';
$lang['DISPO.DEBUT'] = 'Date de début';
$lang['DISPO.FIN'] = 'Date de fin (peut être vide)';
$lang['DISPO.AJOUTER'] = 'Pour ajouter un créneau, il suffit de remplir cette ligne';
$lang['DISPO.SUPPRIMER'] = 'Supprimer';
$lang['DISPO.SUPPRIMER LIGNE'] = 'Supprimer la ligne';
$lang['DISPO.EXPLICATION'] = 'Comment ça marche ?' .
        '<br/>L\'aéronef ne sera pas disponible sur les créneaux indiqués.' .
        '<br/>Pour que l\'aéronef soit toujours disponible, il suffit qu\'il n\'y ait pas de ligne d\'indisponibilité.';
$lang['DISPO.VALIDER'] = 'Enregistrer';
$lang['DISPO.RETOUR'] = 'Retour aux aéronefs';

/**
 * LISTE INSTRUCTEURS.TPL
 */
$lang['LISTE INSTRUCTEURS.PAGE TITLE'] = 'Liste des instructeurs';
$lang['LISTE INSTRUCTEURS.CODE'] = 'Code';
$lang['LISTE INSTRUCTEURS.NOM'] = 'Nom';
$lang['LISTE INSTRUCTEURS.CODE ADHERENT'] = 'Pilote';
$lang['LISTE INSTRUCTEURS.AJOUTER'] = 'Ajouter un instructeur';
$lang['LISTE INSTRUCTEURS.RESULTATS'] = 'instructeurs';
$lang['LISTE INSTRUCTEURS.CONFIRM SUPPR'] = 'Êtes-vous sur de vouloir supprimer cet instructeur : ';
$lang['LISTE INSTRUCTEURS.PAS ENREGISTRE'] = 'Les modifications n\'ont pas été enregistrées';
$lang['LISTE INSTRUCTEURS.ENREGISTRE OK'] = 'Les modifications ont été enregitrées';
$lang['LISTE INSTRUCTEURS.SUPPRIME'] = 'L\'instructeur a été supprimé';

/**
 * MODIFIER INSTRUCTEUR.TPL
 */
$lang['MODIFIER INSTRUCTEUR.PAGE TITLE'] = 'Ajouter/Modifier un instructeur';
$lang['MODIFIER INSTRUCTEUR.TITRE'] = 'Informations sur l\'instructeur';
$lang['MODIFIER INSTRUCTEUR.CODE'] = 'Code :';
$lang['MODIFIER INSTRUCTEUR.NOM'] = 'Nom :';
$lang['MODIFIER INSTRUCTEUR.ADHERENT'] = 'Adhérent associé :';
$lang['MODIFIER INSTRUCTEUR.CODE ADHERENT'] = 'Code de l\'adhérent :';
$lang['MODIFIER INSTRUCTEUR.ENREGISTRER'] = 'Enregistrer l\'instructeur';
$lang['MODIFIER INSTRUCTEUR.ANNULER'] = 'Annuler les modifications';

/**
 * RESERVATION.TPL
 */
$lang['RESERVATION.PAGE TITLE'] = 'Réserver un aéronef';
$lang['RESERVATION.RESA OK'] = 'Votre réservation a été enregistrée';
$lang['RESERVATION.RESA ANNULE'] = 'La réservation a été annulée';
$lang['RESERVATION.RESA SUPPRIME'] = 'La réservation a été supprimée';
$lang['RESERVATION.DUPLICATION'] = 'Cette réservation sera dupliquée et enregistrée en tant que nouvelle réservation, l\'originale restera dans le planning non modifiée';
$lang['RESERVATION.CHOIX'] = 'Choisissez votre aéronef';
$lang['RESERVATION.SEMAINE'] = 'Semaine';
$lang['RESERVATION.ALLER'] = 'Aller à';
$lang['RESERVATION.CHOISIR'] = 'Choisir le';
$lang['RESERVATION.SEM PREC'] = 'Semaine précédente';
$lang['RESERVATION.SEM SUIV'] = 'Semaine suivante';
$lang['RESERVATION.RESERVER LE'] = 'Réserver le';
$lang['RESERVATION.A PARTIR'] = 'à partir de';
$lang['RESERVATION.MODIFIER'] = 'Modifier les informations de cette réservation';
$lang['RESERVATION.COPIER'] = 'Créer une nouvelle réservation à partir des informations de celle-ci';
$lang['RESERVATION.RESERVER'] = 'Réserver';
$lang['RESERVATION.ANNULER'] = 'Annuler';
$lang['RESERVATION.SAUVER'] = 'Modifier';
$lang['RESERVATION.CLONER'] = 'Dupliquer';
$lang['RESERVATION.SUPPRIMER'] = 'Supprimer';
$lang['RESERVATION.DETAIL RESA'] = 'Détail de la réservation';
$lang['RESERVATION.LEGENDE'] = 'Légende';
$lang['RESERVATION.PAS RESA'] = 'Pas de réservation';
$lang['RESERVATION.NUIT AERO'] = '(nuit aéro)';
$lang['RESERVATION.LIBRE'] = 'Créneau libre';
$lang['RESERVATION.LIBRE DEPASSE'] = 'Créneau libre mais date dépassée';
$lang['RESERVATION.RESERVE'] = 'Réservé';
$lang['RESERVATION.RESA AVION'] = 'Aéronef :';
$lang['RESERVATION.RESA JOUR'] = 'Jour du vol :';
$lang['RESERVATION.RESA HEURE'] = 'A partir de :';
$lang['RESERVATION.RESA ADH'] = 'Adhérent :';
$lang['RESERVATION.RESA INSTR'] = 'Instructeur :';
$lang['RESERVATION.RESA DUREE'] = 'Jusqu\'à :';
$lang['RESERVATION.TOUTE LA JOURNEE'] = 'Journée complète :';
$lang['RESERVATION.JOURNEE COMPLETE'] = 'Cochez cette case pour réserver la journée complète';
$lang['RESERVATION.RESA NOM'] = 'Nom :';
$lang['RESERVATION.RESA DESTI'] = 'Destination :';
$lang['RESERVATION.RESA EMAIL'] = 'E-mail :';
$lang['RESERVATION.RESA PORT'] = 'N° de portable :';
$lang['RESERVATION.RESA COMMENTS'] = 'Commentaires :';
$lang['RESERVATION.RESA RESA'] = 'Réservation club :';
$lang['RESERVATION.TITLE RESA CLUB LABEL'] = 'Réservation par l\'aéroclub de l\'aéronef';
$lang['RESERVATION.TITLE ADHERENT'] = 'Choisissez l\'adhérent sur le compte duquel sera attaché la réservation.';
$lang['RESERVATION.TITLE INSTRUC'] = 'Si vous êtes élève, sélectionnez l\'instructeur qui vole avec vous.';
$lang['RESERVATION.TITLE RESA JOUR'] = 'Choisissez le jour du vol';
$lang['RESERVATION.TITLE HEURE DEBUT'] = 'Choisissez l\'heure de début de réservation';
$lang['RESERVATION.TITLE DUREE'] = 'Sélectionnez l\'heure de fin de réservation de l\'aéronef. <br/>Le trésorier validera la durée réellement facturée.';
$lang['RESERVATION.TITLE NOM'] = 'Indiquez votre nom. <br/><b>Donnée obligatoire</b>.';
$lang['RESERVATION.TITLE DESTI'] = 'Indiquez la destination du vol.';
$lang['RESERVATION.TITLE EMAIL'] = 'Indiquez l\'adresse mail sur laquelle on peut vous joindre en cas de nécessité. <br/><b>Donnée obligatoire</b>.';
$lang['RESERVATION.TITLE PORTABLE'] = 'Indiquez le n° de portable sur lequel on peut vous joindre en cas de nécessité. <br/><b>Donnée obligatoire</b>.';
$lang['RESERVATION.TITLE COMMENTS'] = 'Indiquez tout commentaire que vous jugerez utile sur votre réservation.';
$lang['RESERVATION.TITLE RESA CLUB'] = 'Coché : indique si il s\'agit d\'une réservation pour le club (journée portes-ouvertes, etc.). <br/>Décoché : réservation privée.';

$lang['RESERVATION.MOIS.01'] = 'Jan';
$lang['RESERVATION.MOIS.02'] = 'Fév';
$lang['RESERVATION.MOIS.03'] = 'Mars';
$lang['RESERVATION.MOIS.04'] = 'Avr';
$lang['RESERVATION.MOIS.05'] = 'Mai';
$lang['RESERVATION.MOIS.06'] = 'Juin';
$lang['RESERVATION.MOIS.07'] = 'Juil';
$lang['RESERVATION.MOIS.08'] = 'Août';
$lang['RESERVATION.MOIS.09'] = 'Sep';
$lang['RESERVATION.MOIS.10'] = 'Oct';
$lang['RESERVATION.MOIS.11'] = 'Nov';
$lang['RESERVATION.MOIS.12'] = 'Déc';

$lang['RESERVATION.JOUR.0'] = 'Dimanche';
$lang['RESERVATION.JOUR.1'] = 'Lundi';
$lang['RESERVATION.JOUR.2'] = 'Mardi';
$lang['RESERVATION.JOUR.3'] = 'Mercredi';
$lang['RESERVATION.JOUR.4'] = 'Jeudi';
$lang['RESERVATION.JOUR.5'] = 'Vendredi';
$lang['RESERVATION.JOUR.6'] = 'Samedi';

/**
 * PLANNING.TPL
 */
$lang['PLANNING.PAGE TITLE'] = 'Planning de réservation';
$lang['PLANNING.AVIONS'] = 'Aéronefs';
$lang['PLANNING.SEMAINE'] = 'Semaine';
$lang['PLANNING.ALLER'] = 'Aller à';
$lang['PLANNING.RESERVER'] = 'Placer une réservation';

$lang['PLANNING.JOUR.0'] = 'Di';
$lang['PLANNING.JOUR.1'] = 'Lu';
$lang['PLANNING.JOUR.2'] = 'Ma';
$lang['PLANNING.JOUR.3'] = 'Me';
$lang['PLANNING.JOUR.4'] = 'Je';
$lang['PLANNING.JOUR.5'] = 'Ve';
$lang['PLANNING.JOUR.6'] = 'Sa';

/**
 * NEW OPERATION.TPL
 */
$lang['NEW OPERATION.PAGE TITLE'] = 'Créer une opération';
$lang['NEW OPERATION.ENREGISTRE OK'] = 'L\'opération a été ajoutée avec succès';
$lang['NEW OPERATION.PAS ENREGISTRE'] = 'L\'opération n\'a pas été enregistrée';
$lang['NEW OPERATION.TITRE'] = 'Informations générales sur l\'opération';
$lang['NEW OPERATION.ADHERENT'] = 'Pilote :';
$lang['NEW OPERATION.TYPE OPERATON'] = 'Type d\'opération :';
$lang['NEW OPERATION.LIBELLE OPERATION'] = 'Libellé de l\'opération :';
$lang['NEW OPERATION.EXERCICE'] = 'Année comptable :';
$lang['NEW OPERATION.DATE'] = 'Date opération :';
$lang['NEW OPERATION.MONTANT'] = 'Montant :';
$lang['NEW OPERATION.VOL'] = 'Détails s\'il s\'agit d\'un vol';
$lang['NEW OPERATION.AVION'] = 'Aéronef :';
$lang['NEW OPERATION.TYPE VOL'] = 'Type de vol :';
$lang['NEW OPERTATION.AEROPORT'] = 'Destination :';
$lang['NEW OPERATION.PASSAGERS'] = 'Nombre passagers :';
$lang['NEW OPERATION.INSTRUCTEUR'] = 'Instructeur :';
$lang['NEW OPERATION.ATTERISSAGE'] = 'Nombre d\'atterissages :';
$lang['NEW OPERATION.DUREE'] = 'Durée de vol :';
$lang['NEW OPERATION.ENREGISTRER'] = 'Enregistrer l\'opération';
$lang['NEW OPERATION.ANNULER'] = 'Annuler';

/**
 * RAPPROCHEMENT.TPL
 */
$lang['RAPPROCHEMENT.PAGE TITLE'] = 'Rapprochement des réservations';
$lang['RAPPROCHEMENT.ACTION'] = 'Action';
$lang['RAPPROCHEMENT.RESERVATION'] = 'Réservation';
$lang['RAPPROCHEMENT.OPERATION'] = 'Opération';
$lang['RAPPROCHEMENT.RAPPROCHER'] = 'Rapprocher';
$lang['RAPPROCHEMENT.IMPUTER'] = 'Imputer à :';
$lang['RAPPROCHEMENT.AVION'] = 'Aéronef :';
$lang['RAPPROCHEMENT.TYPE VOL'] = 'Type vol :';
$lang['RAPPROCHEMENT.DEP ARR'] = 'Départ / Arrivée :';
$lang['RAPPROCHEMENT.MONTANT'] = 'Montant :';
$lang['RAPPROCHEMENT.LIBELLE'] = 'Libellé :';
$lang['RAPPROCHEMENT.INSTRUCTEUR'] = 'Instructeur :';
$lang['RAPPROCHEMENT.ATTERRISSAGES'] = 'Nb atterissages :';
$lang['RAPPROCHEMENT.PASSAGERS'] = 'Nb passagers :';
$lang['RAPPROCHEMENT.DUREE'] = 'Durée :';
$lang['RAPPROCHEMENT.AUTRE'] = '--- Ou autre ---';
$lang['RAPPROCHEMENT.OPERATION OK'] = 'opérations créées avec succès';
$lang['RAPPROCHEMENT.ENREGISTRER'] = 'Valider le rapprochement';

/**
 * GRAPHIQUE.TPL 
 */
$lang['GRAPHIQUE.PAGE TITLE'] = 'Graphique temps de vol et solde du compte';
$lang['GRAPHIQUE.TABLE TITLE'] = 'Voir le graphique de l\'adhérent';
$lang['GRAPHIQUE.ADHERENT'] = 'Adhérent :';
$lang['GRAPHIQUE.AFFICHER'] = 'Afficher';
$lang['GRAPHIQUE.IMAGE'] = 'Evolution de mes temps de vols par mois et en cumulé et de mon solde aéroclub';
$lang['GRAPHIQUE.LEGENDE'] = 'Légende';
$lang['GRAPHIQUE.BLEU1'] = 'Barre bleue';
$lang['GRAPHIQUE.BLEU2'] = 'Temps de vol sur le mois';
$lang['GRAPHIQUE.VIOLET1'] = 'Ligne violette';
$lang['GRAPHIQUE.VIOLET2'] = 'Temps de vol cumulé sur l\'année';
$lang['GRAPHIQUE.ORANGE1'] = 'Ligne orange';
$lang['GRAPHIQUE.ORANGE2'] = 'Solde cumulé sur l\'année';

/**
 * HEURES.TPL 
 */
$lang['HEURES PILOTE.PAGE TITLE'] = 'Heures de vol adhérents';
$lang['HEURES PILOTE.TABLEAU'] = 'Liste';
$lang['HEURES PILOTE.GRAPHIQUE'] = 'Graphique';
$lang['HEURES PILOTE.TABLEAU LEGENDE'] = 'Temps de vol des pilotes (liste)';
$lang['HEURES PILOTE.GRAPHIQUE LEGENDE'] = 'Temps de vol des pilotes (graphique)';
$lang['HEURES AVION.PAGE TITLE'] = 'Heures de vol aéronefs';
$lang['HEURES AVION.TABLEAU'] = 'Liste';
$lang['HEURES AVION.MOIS12'] = '12 derniers mois';
$lang['HEURES AVION.TABLEAU LEGENDE'] = 'Temps de vol des aéronefs (liste)';
$lang['HEURES AVION.MOIS12 LEGENDE'] = 'Temps de vol des aéronefs (graphique)';
$lang['HEURES AVION.ANNEES'] = 'Par années';
$lang['HEURES AVION.ANNEES LEGENDE'] = 'Temps de vol des aéronefs par années (graphique)';
$lang['HEURES AVION.CHOIX IMMAT'] = 'Choix de l\'immatriculation à afficher :';
$lang['HEURES AVION.CHOIX ANNEES'] = 'Choix des années à afficher (3 max) :';
$lang['HEURES.ADHERENT NOM'] = 'Nom';
$lang['HEURES.ADHERENT PRENOM'] = 'Prénom';
$lang['HEURES.ADHERENT PSEUDO'] = 'Identifiant';
$lang['HEURES.ANNEE DERNIERE'] = 'L\'année dernière';
$lang['HEURES.CETTE ANNEE'] = 'Cette année';
$lang['HEURES.UN AN GLISSANT'] = '1 an glissant<br/>depuis ';
$lang['HEURES.AVION'] = 'Aéronef';

/**
 * TYPES VOLS PILOTES
 */
$lang['TYPES VOLS PILOTES.PAGE TITLE'] = 'Heures de vols des pilotes par type';

/**
 * SOLDES PILOTES.TPL 
 */
$lang['SOLDES PILOTES.PAGE TITLE'] = 'Soldes par pilote';
$lang['SOLDES PILOTES.NOM'] = 'Nom';
$lang['SOLDES PILOTES.PRENOM'] = 'Prénom';
$lang['SOLDES PILOTES.PSEUDO'] = 'Id.';
$lang['SOLDES PILOTES.EMAIL'] = 'E-mail';
$lang['SOLDES PILOTES.SOLDE'] = 'Solde';
$lang['SOLDES PILOTES.ACTIF'] = 'Montrer que les pilotes actifs :';
$lang['SOLDES PILOTES.SOLDE NEGATIF'] = 'Voir les soldes :';
$lang['SOLDES PILOTES.OUI'] = 'Oui';
$lang['SOLDES PILOTES.NON'] = 'Non';
$lang['SOLDES PILOTES.NEGATIF'] = 'négatifs';
$lang['SOLDES PILOTES.ZERO'] = 'égaux à 0';
$lang['SOLDES PILOTES.POSITIF'] = 'positifs';
$lang['SOLDES PILOTES.TOUS'] = 'Tous';

/**
 * VERSION.TPL 
 */
$lang['VERSION.PAGE TITLE'] = 'Notes de versions';

/**
 * DOCUMENTS_FRAME.TPL
 */
$lang['DOCUMENTS IFRAME.PAGE TITLE'] = 'Documentation aéroclub';

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
?>
