<?php

/**
 * Display a big table with details of reservation for 1 plane.
 * Display the reservation edit/add form too.
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
 * Transforme une date du format JJ/MM/AAAA au format AAAA-MM-JJ
 * 
 * @param string $str Une date au format JJ/MM/AAAA
 * 
 * @return string Une date au format AAAA-MM-JJ
 */
function dateIHMtoSQL($str) {
    if (strlen($str) < 5) {
        return '';
    }
    $dt = date_create_from_format('d/m/Y', $str);
    return $dt->format('Y-m-d');
}

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
if (!$login->isLogged()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';
global $zdb;

/**
 * Annulation de l'enregistrement, on revient à la liste
 */
if (array_key_exists('annuler', $_POST)) {
    if ($_POST['origine'] == 'planning') {
        header('Location: planning.php?msg=resa_annule&jour=' . $_POST['resa_jour']);
    } else {
        header('Location: reservation.php?msg=resa_annule&jour=' . $_POST['resa_jour'] . '&avion_id=' . $_POST['avion_id']);
    }
}

/**
 * Suppression de la réservation
 */
if (array_key_exists('supprimer', $_POST)) {
    PiloteReservation::supprimeReservation($_POST['resa_id']);
    header('Location: reservation.php?msg=resa_supprime&jour=' . $_POST['resa_jour'] . '&avion_id=' . $_POST['avion_id']);
}

/**
 * Enregistrement de la réservation
 */
if (array_key_exists('reserver', $_POST)) {
    $reservation = new PiloteReservation();
    $ok = true;
    $msg_erreur = array();
    if ($_POST['resa_id'] != 'null') {
        $reservation = new PiloteReservation(intval($_POST['resa_id']));
    }
    if ($login->isAdmin()) {
        // Si Admin, notre ligne est de type
        // {id}$$${nom / prénom}$$${eMail}$$${portable}
        $ligne = $_POST['resa_id_adh'];
        $reservation->id_adherent = substr($ligne, 0, strpos($ligne, '$$$'));
    } else {
        $reservation->id_adherent = $_POST['resa_id_adh'];
    }
    $reservation->id_avion = $_POST['avion_id'];
    $reservation->id_instructeur = $_POST['instructeur_id'];
    $reservation->heure_debut = dateIHMtoSQL($_POST['resa_jour_debut']) . ' ' . $_POST['resa_heure_debut'] . ':00';
    $reservation->heure_fin = dateIHMtoSQL($_POST['resa_jour_debut']) . ' ' . $_POST['resa_heure_fin'] . ':00';
    $reservation->nom = $_POST['resa_nom'];
    $reservation->destination = $_POST['resa_destination'];
    $reservation->email_contact = $_POST['resa_email'];
    $reservation->no_portable = $_POST['resa_portable'];
    $reservation->commentaires = $_POST['resa_commentaires'];
    $reservation->est_reservation_club = $_POST['est_resa_club'] == '1';
    if($reservation->est_reservation_club){
        $reservation->id_adherent = null;
    }
    
    // Sauvegarde que sur une date future (on sait jamais)
    if ($reservation->heure_fin <= date('Y-m-d H:i:s')) {
        $ok = false;
        $msg_erreur[] = 'Vous ne pouvez pas placer une réservation à une date échue.';
    }
    // Vérification heure début < heure fin
    if ($reservation->heure_debut >= $reservation->heure_fin) {
        $ok = false;
        $msg_erreur[] = 'L\'heure de début doit être strictement inférieure à l\'heure de fin.';
    }
    // Vérification avion est dispo
    $dispos = PiloteAvionDispo::getDisponibilitesPourAvion(intval($reservation->id_avion));
    foreach ($dispos as $d) {
        if (dateIHMtoSQL($d->date_debut) <= dateIHMtoSQL($_POST['resa_jour_debut'])
                && dateIHMtoSQL($_POST['resa_jour_debut']) <= dateIHMtoSQL($d->date_fin)) {
            $ok = false;
            $msg_erreur[] = 'L\'avion n\'est pas réservable du ' . $d->date_debut . ' au ' . $d->date_fin . '.';
        }
    }
    // Vérification chevauchement avec une autre réservation
    $resas = PiloteReservation::getReservationsPourAvion(intval($reservation->id_avion), dateIHMtoSQL($_POST['resa_jour_debut']), dateIHMtoSQL($_POST['resa_jour_debut']));
    foreach ($resas as $r) {
        if ($r->reservation_id != $reservation->reservation_id
                && $r->heure_debut < $reservation->heure_fin
                && $reservation->heure_debut < $r->heure_fin) {
            $ok = false;
            $msg_erreur[] = 'L\'avion est déjà réservé par ' . $r->nom . ' de ' .
                    substr($r->heure_debut, 11, 5) .
                    ' à ' .
                    substr($r->heure_fin, 11, 5) .
                    '. Vérifiez vos heures de réservation.';
        }
    }

    if ($ok) {
        $reservation->store();
        if ($_POST['origine'] == 'planning') {
            header('Location: planning.php?msg=resa_ok&jour=' . dateIHMtoSQL($_POST['resa_jour_debut']));
        } else {
            header('Location: reservation.php?msg=resa_ok&jour=' . dateIHMtoSQL($_POST['resa_jour_debut']) . '&avion_id=' . $_POST['avion_id']);
        }
    }
}

/**
 * Récupération des valeurs du jour et avion
 */
$jour_selectionne = array_key_exists('jour', $_GET) ? $_GET['jour'] : date('Ymd');
$avion_id = array_key_exists('avion_id', $_GET) ? $_GET['avion_id'] : null;
$resa_jour = array_key_exists('resa_jour', $_GET) ? $_GET['resa_jour'] : null;
$resa_heure = array_key_exists('resa_heure', $_GET) ? $_GET['resa_heure'] : null;
$resa_heure_fin = array_key_exists('resa_heure_fin', $_GET) ? $_GET['resa_heure_fin'] : null;
$resa_ok = array_key_exists('msg', $_GET) && $_GET['msg'] == 'resa_ok';
$resa_annule = array_key_exists('msg', $_GET) && $_GET['msg'] == 'resa_annule';
$resa_supprime = array_key_exists('msg', $_GET) && $_GET['msg'] == 'resa_supprime';
$resa_id = array_key_exists('resa_id', $_GET) ? $_GET['resa_id'] : null;

$dessine_avion = true;
$dessine_semaine = true;
$dessine_reservation = false;

if ($avion_id == null) {
    $dessine_semaine = false;
}
if ($resa_jour != null || $resa_id != null || $reservation != null) {
    $dessine_avion = false;
    $dessine_semaine = false;
    $dessine_reservation = true;
}

/**
 * Calcul des jours affichés
 */
// Si le jour sélectionné est un dimanche, on enlève un jour
$j_calcul = $jour_selectionne;
if (date('w', strtotime($j_calcul)) == '0') {
    $j_calcul = date('Ymd', strtotime('-1 days', strtotime($jour_selectionne)));
}
// Récupération du lundi avant la date choisie
$depart = date('Ymd', strtotime('-' . (date('w', strtotime($j_calcul)) - 1) . ' days', strtotime($j_calcul)));
$jours = array();
$ephemeride = array();
for ($i = 0; $i < 7; $i++) {
    $nom_jour = _T('RESERVATION.JOUR.' . date('w', strtotime('+' . $i . ' days', strtotime($depart))));
    $no_jour = date('j', strtotime('+' . $i . ' days', strtotime($depart)));
    $num_jour_ann = date('z/Y', strtotime('+' . $i . ' days', strtotime($depart)));
    $nom_mois = _T('RESERVATION.MOIS.' . date('m', strtotime('+' . $i . ' days', strtotime($depart))));
    $jours[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))] = $nom_jour . ' ' . $no_jour . ' ' . $nom_mois . '<br/>#' . $num_jour_ann;

    $levercoucher = PiloteReservation::getLeverCoucher(date('j', strtotime('+' . $i . ' days', strtotime($depart))), date('n', strtotime('+' . $i . ' days', strtotime($depart))), doubleval(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_AEROCLUB_LATITUDE)), doubleval(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_AEROCLUB_LONGITUDE)));
    $ephemeride[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))]->lever = $levercoucher['lever'];
    $ephemeride[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))]->coucher = $levercoucher['coucher'];
}

/**
 * Ajout des heures
 */
$heures = array();
for ($i = intval(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_DEBUT)); $i <= intval(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_FIN)); $i++) {
    $heures[] = ($i < 10 ? '0' : '') . $i . ':00';
    $heures[] = ($i < 10 ? '0' : '') . $i . ':30';
}

/**
 * Récupération de liste des résas pour cet avion
 */
$liste_resas = array();
$liste_dispos = array();
if ($avion_id != null) {
    $liste_resas = PiloteReservation::getReservationsPourAvion($avion_id, date('Y-m-d', strtotime($depart)), date('Y-m-d', strtotime('+6 days', strtotime($depart))));
    $liste_dispos = PiloteAvionDispo::getDisponibilitesPourAvion($avion_id);
}

// Instructeurs: liste + connecté
$liste_instructeurs = PiloteInstructeur::getTousInstructeurs('nom', 'asc', 1, 9999);
$is_instructeur = PiloteInstructeur::isPiloteInstructeur($login->login);

/**
 * Liste des réservations pour la semaine affichée
 */
$reservations = array();
foreach ($jours as $jourCode => $jourIHM) {
    foreach ($heures as $h) {
        // Création d'autant d'objet que de case dans le tableau
        $reservations[$jourIHM][$h] = new CaseAgenda();
        // Sont interdits les samedi (jour 6) et dimanche (jour 0) à partir de l'heure du paramètre respectif
        $reservations[$jourIHM][$h]->interdit = false;
        $reservations[$jourIHM][$h]->cliquable = date('Y-m-d', strtotime($jourCode)) . ' ' . $h > date('Y-m-d H:i');
        if ((intval($h) >= PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_SAMEDI) && date('w', strtotime($jourCode)) == 6)
                || (intval($h) >= PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_DIMANCHE) && date('w', strtotime($jourCode)) == 0)) {
            $reservations[$jourIHM][$h]->interdit = true;
            if (strtolower(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_AUTORISER_RESA_INTERDIT)) != 'o') {
                $reservations[$jourIHM][$h]->cliquable = false;
            }
        }
        $reservations[$jourIHM][$h]->code_jour = $jourCode;
        $reservations[$jourIHM][$h]->libre = true;
        $reservations[$jourIHM][$h]->nuit = false;

        // Vérification de la dispo pour rendre un avion non dispo
        // sur 1 jour ou plus
        foreach ($liste_dispos as $dispo) {
            if (strtotime(dateIHMtoSQL($dispo->date_debut)) <= strtotime($jourCode)
                    && strtotime($jourCode) <= strtotime(dateIHMtoSQL($dispo->date_fin))) {
                $reservations[$jourIHM][$h]->interdit = true;
                $reservations[$jourIHM][$h]->cliquable = false;
            }
        }

        // Nuit aéro (lever - 30 min / coucher + 30 min)
        if (date('Y-m-d H:i', strtotime($jourCode . ' ' . $h)) <
                date('Y-m-d H:i', strtotime('-30 mins', strtotime($jourCode . ' ' . $ephemeride[$jourCode]->lever)))) {
            $reservations[$jourIHM][$h]->nuit = true;
        }
        if (date('Y-m-d H:i', strtotime($jourCode . ' ' . $h)) >
                date('Y-m-d H:i', strtotime('+30 mins', strtotime($jourCode . ' ' . $ephemeride[$jourCode]->coucher)))) {
            $reservations[$jourIHM][$h]->nuit = true;
        }

        // Vérification dans toutes les résas prévue
        foreach ($liste_resas as $resa) {
            // Si la date de début de résa correspond à la case
            if (date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' == $resa->heure_debut) {
                $reservations[$jourIHM][$h]->libre = false;
                $reservations[$jourIHM][$h]->masquer = false;
                $reservations[$jourIHM][$h]->nom = $resa->nom;
                $reservations[$jourIHM][$h]->infobulle = $resa->nom
                        . (strlen(trim($resa->no_portable)) > 0 ? "<br/><img src=\"picts/mobile-phone.png\" title=\"N° de téléphone portable\"> " . $resa->no_portable : "")
                        . (strlen(trim($resa->destination)) > 0 ? "<br/><img src=\"picts/destination.png\" title=\"Destination du vol\"> " . $resa->destination : "")
                        . (strlen(trim($resa->commentaires)) > 0 ? "<br/><img src=\"picts/comment.png\" title=\"Commentaires sur la réservation\"> <i>" . $resa->commentaires . "</i>" : "")
                        . ($resa->id_instructeur != '' ? "<br/><img src=\"picts/instructeur.png\" title=\"Vol avec instructeur\"> " . $liste_instructeurs[$resa->id_instructeur]->nom : "");
                $reservations[$jourIHM][$h]->editable = $resa->id_adherent == $login->id || $login->isAdmin();
                // Si instructeur, on a le droit de modifier les résa dont on est instructeur
                if ($is_instructeur && $resa->id_instructeur != ''
                        && ($liste_instructeurs[$resa->id_instructeur]->adherent_id == $login->id
                        || PiloteParametre::getValeurParametre(PiloteParametre::PARAM_INSTRUCTEUR_RESERVATION) == '1' )) {
                    $reservations[$jourIHM][$h]->editable = true;
                }
                // On redéfinit "cliquable" pour autoriser la modification
                // d'une résa si la date de fin est inférieur à la date actuelle
                $reservations[$jourIHM][$h]->cliquable = date('Y-m-d H:i:s') <= $resa->heure_fin;

                // Définition infos résa
                $reservations[$jourIHM][$h]->resa_id = $resa->reservation_id;
                $reservations[$jourIHM][$h]->est_resa_club = $resa->est_reservation_club;

                // Calcul du rowspan
                // 1 case = 30 min
                // donc rowspan = (heures * 60 + minutes) / 30
                $d_debut = new DateTime($resa->heure_debut);
                $d_fin = new DateTime($resa->heure_fin);
                $diff = $d_debut->diff($d_fin);
                $reservations[$jourIHM][$h]->rowspan = (intval($diff->format('%h')) * 60 + intval($diff->format('%i'))) / 30;
            }
            // Si la date de la case est entre la date de début et de fin de la résa
            // on n'affiche pas la case (à cause du rowspan)
            if (date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' > $resa->heure_debut
                    && date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' < $resa->heure_fin) {
                $reservations[$jourIHM][$h]->libre = false;
                $reservations[$jourIHM][$h]->masquer = true;
            }
        }
    }
}

//bug 32 - on affiche tous les avions
//$liste_avions = PiloteAvion::getTousAvionsReservables(date('Y-m-d', strtotime($jour_selectionne)));
$liste_avions = PiloteAvion::getTousAvionsReservables();

$tpl->assign('page_title', _T("RESERVATION.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

// Avions
$tpl->assign('liste_avions', $liste_avions);
$tpl->assign('avion_id', $avion_id);

// Instructeurs
$tpl->assign('liste_instructeurs', $liste_instructeurs);

// Entête calendrier / Semaine choisie
$tpl->assign('jours', $jours);
$tpl->assign('semaine_avant', date('Ymd', strtotime('-3 days', strtotime($depart))));
$tpl->assign('semaine_apres', date('Ymd', strtotime('+7 days', strtotime($depart))));
$tpl->assign('numero_semaine', date('W', strtotime($depart)));
$tpl->assign('annee', date('Y', strtotime($depart)));
$tpl->assign('debut_semaine', date('d/m/Y', strtotime($depart)));
$tpl->assign('fin_semaine', date('d/m/Y', strtotime('+6 days', strtotime($depart))));
$tpl->assign('require_calendar', true);
$tpl->assign('jour_selectionne', $jour_selectionne);
$tpl->assign('jour_selectionne_IHM', date('d/m/Y', strtotime($jour_selectionne)));

$tpl->assign('ephemeride', $ephemeride);

// Couleurs des paramètres
$tpl->assign('couleur_libre', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE));
$tpl->assign('couleur_libre_clair', PiloteParametre::eclaircirCouleurHexa(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE)));
$tpl->assign('couleur_libre_nuit', PiloteParametre::assombrirCouleur(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE)));
$tpl->assign('couleur_reserve', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_RESERVE));
$tpl->assign('couleur_interdit', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_INTERDIT));
$tpl->assign('couleur_interdit_nuit', PiloteParametre::assombrirCouleur(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_INTERDIT)));

// Calendrier principal : heures et résas
$tpl->assign('reservations', $reservations);
$tpl->assign('heures', $heures);

// Bools d'affichage de telle ou telle partie
$tpl->assign('dessine_avion', $dessine_avion);
$tpl->assign('dessine_semaine', $dessine_semaine);
$tpl->assign('dessine_reservation', $dessine_reservation);

// Messages informatifs
$tpl->assign('resa_ok', $resa_ok);
$tpl->assign('resa_annule', $resa_annule);
$tpl->assign('resa_supprime', $resa_supprime);
$tpl->assign('erreur_resa', false);

// Indique si le pilote connecté est un instructeur
$tpl->assign('is_instructeur', $is_instructeur);

// Liste des adhérents si l'utilisateur est Admin
$liste_adherents = array();
if ($login->isAdmin() || $is_instructeur) {
    /**
     * Récupération de la liste des adhérents actifs
     */
    try {
        $select = new Zend_Db_Select($zdb->db);
        $select->from(PREFIX_DB . Galette\Entity\Adherent::TABLE)
                ->where('activite_adh = 1')
                ->order('nom_adh');
        $result = $select->query()->fetchAll();
        foreach ($result as $row) {
            $liste_adherents[$row->id_adh]->key = $row->id_adh . '$$$' . $row->nom_adh . ' ' . $row->prenom_adh . '$$$' . $row->email_adh . '$$$' . $row->gsm_adh;
            $liste_adherents[$row->id_adh]->value = $row->nom_adh . ' ' . $row->prenom_adh . ' (' . $row->login_adh . ')';
        }
    } catch (Exception $e) {
        Analog\Analog::log(
                'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                $e->getTraceAsString(), Analog\Analog::ERROR
        );
    }
}

/**
 * Ajout/Modification d'une réservation
 */
if ($resa_jour != null || $resa_id != null) {

    // Vérification réservation possible
    $aff_msg_warning = false;
    $aff_msg_bloque = false;
    if (strtolower(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_BLOCAGE_ACTIF)) != 'd') {

        // Warning si compte négatif
        if (strtolower(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_BLOCAGE_ACTIF)) == 'w'
                && PiloteOperation::getSoldeCompteForLogin($login->login) < 0) {
            $aff_msg_warning = true;
            $tpl->assign('msg_warning', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_BLOCAGE_MESSAGE_WARNING));
        }

        // Blocage si date validité dépassée
        if (strtolower(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_BLOCAGE_ACTIF)) == 'b') {
            $complement = new PiloteAdherentComplement($login->login);
            if ($complement->isDateLicenseOuMedicaleDepassee()) {
                $aff_msg_bloque = true;
                $tpl->assign('msg_blocage', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_BLOCAGE_MESSAGE_BLOQUE));
                // Résa interdite sauf si elève
                if (!$complement->est_eleve) {
                    $tpl->assign('dessine_reservation', false);
                }
            }
        }
    }
    $tpl->assign('aff_msg_warning', $aff_msg_warning);
    $tpl->assign('aff_msg_bloque', $aff_msg_bloque);

    $resa_avion = new PiloteAvion(intval($avion_id));
    $tpl->assign('resa_avion', $resa_avion);
    $adherent = new Galette\Entity\Adherent($login->login);

    // Liste des adhérents
    $tpl->assign('liste_adherents', $liste_adherents);

    // Heures de réservation
    // Si un ID de réservation est indiquée, on la charge
    if ($resa_id != null) {
        $resa = new PiloteReservation(intval($resa_id));
        $resa_heure = substr($resa->heure_debut, 11, 5);
        $resa_jour = substr($resa->heure_debut, 0, 10);

        // On vérifie les droits de modification de la réservation
        $has_modif_right = false;
        // Propriétaire de la réservation -> OK
        if ($resa->id_adherent == $login->id) {
            $has_modif_right = true;
        }
        // Admin -> OK
        if ($login->isAdmin()) {
            $has_modif_right = true;
        }
        // Instructeur de la réservation
        if ($is_instructeur && $resa->id_instructeur != ''
                && ($liste_instructeurs[$resa->id_instructeur]->adherent_id == $login->id
                || PiloteParametre::getValeurParametre(PiloteParametre::PARAM_INSTRUCTEUR_RESERVATION) == '1')) {
            $has_modif_right = true;
        }
        // Si on n'a pas les droits, on ne rentre pas en édition
        if (!$has_modif_right) {
            header('Location: reservation.php?jour=' . $resa_jour . '&avion_id=' . $avion_id);
        }
    } else {
        // Pas d'ID, on charge une résa vide
        $resa = new PiloteReservation();
        $resa->id_adherent = $login->id;
        $resa->reservation_id = 'null';
        $resa->id_avion = $avion_id;
        $resa->heure_debut = date('Y-m-d H:i:s', strtotime($resa_jour . ' ' . $resa_heure));
        $resa->heure_fin = date('Y-m-d H:i:s', strtotime($resa_jour . ' ' . $resa_heure));
        $resa->nom = $adherent->name . ' ' . $adherent->surname;
        $resa->email_contact = $adherent->email;
        $resa->no_portable = $adherent->gsm;
    }

    //$nom_jour = _T('RESERVATION.JOUR.' . date('w', strtotime($resa_jour)));
    //$no_jour = date('j', strtotime($resa_jour));
    //$nom_mois = _T('RESERVATION.MOIS.' . date('m', strtotime($resa_jour)));
    //$tpl->assign('resa_jour_IHM', $nom_jour . ' ' . $no_jour . ' ' . $nom_mois . ' ' . date('Y', strtotime($resa_jour)));
    $tpl->assign('resa_jour_debut', date('d/m/Y', strtotime($resa->heure_debut)));
    $tpl->assign('resa', $resa);

    // Calcul des heures de fin
    $heures_fin = array();
    for ($i = 30; $i <= (24 - intval($resa_heure)) * 60; $i+=30) {
        $k = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($resa_jour . ' ' . $resa_heure)));
        $v = 'soit jusqu\'à ' . date('H:i', strtotime('+' . $i . ' minutes', strtotime($resa_jour . ' ' . $resa_heure)));
        $h = floor($i / 60);
        $m = $i - $h * 60;
        $heures_fin[$k] = '';
        if ($h > 0) {
            $heures_fin[$k] .= $h . 'h ';
        } else {
            //$heures_fin[$k] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if ($m > 0) {
            $heures_fin[$k] .= $m . 'min ';
        } else {
            //$heures_fin[$k] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        //$heures_fin[$k] .= $v;
        // Ce commentaire permet d'afficher le coût horaire si voulu
        //. ' - ' . number_format($i / 60 * $resa_avion->cout_horaire, 2, ',', ' ') . ' EUR';
        // Vérification contre le chevauchement
        foreach ($liste_resas as $resa_existante) {
            if ($k == $resa_existante->heure_debut) {
                break 2;
            }
        }
    }

    $tpl->assign('resa_heures_fin', $heures_fin);
    $tpl->assign('resa_heure_fin', substr($resa->heure_fin, 11, 5));
}

/**
 * Validation échouée d'une réservation 
 */
if ($reservation != null && !$ok) {
    // Message informatif
    $tpl->assign('erreur_resa', true);
    $tpl->assign('msg_erreur', $msg_erreur);
    // Liste des adhérents
    $tpl->assign('liste_adherents', $liste_adherents);
    // Avion
    $tpl->assign('resa_avion', new PiloteAvion(intval($reservation->id_avion)));
    // Détails de la résa
    $tpl->assign('resa_jour_debut', date('d/m/Y', strtotime($reservation->heure_debut)));
    $tpl->assign('resa', $reservation);
    $resa_heure = substr($reservation->heure_debut, 11, 5);
    $resa_jour = substr($reservation->heure_debut, 0, 10);
    $tpl->assign('resa_heure_fin', substr($reservation->heure_fin, 11, 5));
}

// Jour + heure de résa
$tpl->assign('resa_jour', $resa_jour);
$tpl->assign('resa_heure', $resa_heure);
$tpl->assign('resa_origine', array_key_exists('origine', $_GET) ? $_GET['origine'] : '');

$content = $tpl->fetch('reservation.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
