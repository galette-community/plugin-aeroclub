<?php

/**
 * Display the planning of the reservation for all planes in a small table.
 * Pilot can click on a cell to add a reservation for the selected plane.
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

/**
 * Récupération des valeurs du jour et avion
 */
$jour_selectionne = filter_has_var(INPUT_GET, 'jour') ? filter_input(INPUT_GET, 'jour') : date('Ymd');

$resa_ok = filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'resa_ok';
$resa_annule = filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'resa_annule';

$ajax = filter_has_var(INPUT_GET, 'mode') && filter_input(INPUT_GET, 'mode') == 'ajax';

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
$tooltip_jours = array();
for ($i = 0; $i < 7; $i++) {
    // Nom du jour en abrégé : Lu, Ma, Me, Je, Ve, Sa, Di
    $nom_jour = _T('PLANNING.JOUR.' . date('w', strtotime('+' . $i . ' days', strtotime($depart))));
    $jours[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))] = $nom_jour;

    // Tooltip affiché au survol
    $num_jour_ann = date('D j M <b\r/>#z/Y', strtotime('+' . $i . ' days', strtotime($depart)));
    $tooltip_jours[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))] = $num_jour_ann;

    // Calcul du levé/couché
    $levercoucher = PiloteReservation ::getLeverCoucher(date('j', strtotime('+' . $i . ' days', strtotime($depart))), date('n', strtotime('+' . $i . ' days', strtotime($depart))), doubleval(PiloteParametre:: getValeurParametre(PiloteParametre::PARAM_AEROCLUB_LATITUDE)), doubleval(PiloteParametre::getValeurParametre(PiloteParametre ::PARAM_AEROCLUB_LONGITUDE)));
    $ephemeride[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))] = new stdClass ( );
    $ephemeride[date('Ymd', strtotime('+' . $i . ' days', strtotime($depart)))]->lever = $levercoucher ['lever'];
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

// bug 32 - afficher tous les avions
//$liste_avions = PiloteAvion::getTousAvionsReservables(date('Y-m-d', strtotime($jour_selectionne)));
$liste_avions = PiloteAvion::getTousAvionsReservables();
foreach ($liste_avions as $avion) {
    $avion->tooltip = '';
    if ($avion->hasPicture()) {
        $size = PiloteAvionPicture::hauteurLargeurAvionPicture($avion->immatriculation);
        $avion->tooltip = '<img src=\'picture.php?quick=1&avion_id=' . $avion->immatriculation . '\' width=\'' . $size->width . '\' height=\'' . $size->height . '\'/>';
    }
    $avion->tooltip .= '<center><b>' . $avion->marque_type . '</b> (' . $avion->immatriculation . ')' .
            '<br/>' . $avion->type_aeronef . '</center>';
}

/**
 * Récupération de liste des résas pour les avions
 */
$liste_resas = array();
$liste_dispos = array();
foreach ($liste_avions as $avion) {
    $liste_resas[$avion->avion_id] = PiloteReservation::getReservationsPourAvion($avion->avion_id, date('Y-m-d', strtotime($depart)), date('Y-m-d', strtotime('+6 days', strtotime($depart))));
    $liste_dispos[$avion->avion_id] = PiloteAvionDispo::getDisponibilitesPourAvion($avion->avion_id);
}

// Instructeurs: liste + connecté
$liste_instructeurs = PiloteInstructeur::getTousInstructeurs('nom', 'asc', 0, 9999);
$is_instructeur = PiloteInstructeur::isPiloteInstructeur($login->login);

/**
 * Remplissage du tableau par avions
 */
$planning = array();
foreach ($liste_avions as $avion) {
    $planning[$avion->avion_id] = new stdClass();
    $planning[$avion->avion_id]->reservations = array();

    foreach ($jours as $jourCode => $jourIHM) {
        foreach ($heures as $h) {
            // Création d'autant d'objet que de case dans le tableau
            $planning[$avion->avion_id]->reservations[$jourCode][$h] = new CaseAgenda();
            // Sont interdits les samedi (jour 6) et dimanche (jour 0) à partir de 14h
            $planning[$avion->avion_id]->reservations[$jourCode][$h]->interdit = false;
            $planning[$avion->avion_id]->reservations[$jourCode][$h]->cliquable = date('Y-m-d', strtotime($jourCode)) . ' ' . $h > date('Y-m-d H:i');
            if ((intval($h) >= PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_SAMEDI) && date('w', strtotime($jourCode)) == 6) || (intval($h) >= PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CALENDRIER_HEURE_DIMANCHE) && date('w', strtotime($jourCode)) == 0)) {
                $planning[$avion->avion_id]->reservations[$jourCode][$h]->interdit = true;
                if (strtolower(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_AUTORISER_RESA_INTERDIT)) != 'o') {
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->cliquable = false;
                }
            }
            $planning[$avion->avion_id]->reservations[$jourCode][$h]->code_jour = $jourCode;
            $planning[$avion->avion_id]->reservations[$jourCode][$h]->libre = true;
            $planning[$avion->avion_id]->reservations[$jourCode][$h]->nuit = false;

            // Vérification de la dispo pour rendre un avion non dispo
            // sur 1 jour ou plus
            foreach ($liste_dispos[$avion->avion_id] as $dispo) {
                if (strtotime(dateIHMtoSQL($dispo->date_debut)) <= strtotime($jourCode) && strtotime($jourCode) <= strtotime(dateIHMtoSQL($dispo->date_fin))) {
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->interdit = true;
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->cliquable = false;
                }
            }

            // Nuit aéro (lever - 30 min / coucher + 30 min)
            if (date('Y-m-d H:i', strtotime($jourCode . ' ' . $h)) <
                    date('Y-m-d H:i', strtotime('-30 mins', strtotime($jourCode . ' ' . $ephemeride[$jourCode]->lever)))) {
                $planning[$avion->avion_id]->reservations[$jourCode][$h]->nuit = true;
            }
            if (date('Y-m-d H:i', strtotime($jourCode . ' ' . $h)) >
                    date('Y-m-d H:i', strtotime('+30 mins', strtotime($jourCode . ' ' . $ephemeride[$jourCode]->coucher)))) {
                $planning[$avion->avion_id]->reservations[$jourCode][$h]->nuit = true;
            }

            // Vérification dans toutes les résas prévue
            foreach ($liste_resas[$avion->avion_id] as $resa) {
                // Si la date de début de résa correspond à la case
                if (date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' == $resa->heure_debut) {
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->libre = false;
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->masquer = false;
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->nom = $resa->nom;
                    $planning[$avion->avion_id]->reservations [$jourCode][$h]->editable = $resa->id_adherent == $login->id;
                    // Si instructeur, on a le droit de modifier les résa dont on est instructeur
                    if ($is_instructeur && $resa->id_instructeur != '' && $liste_instructeurs[$resa->id_instructeur]->adherent_id == $login->id) {
                        $planning[$avion->avion_id]->reservations[$jourCode][$h]->editable = true;
                    }
                    // On redéfinit "cliquable" pour autoriser la modification
                    // d'une résa si la date de fin est inférieur à la date actuelle
                    $planning[$avion->avion_id]->reservations [$jourCode][$h]->cliquable = date('Y-m-d H:i:s') <= $resa->heure_fin;

                    // Définition infos résa
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->resa_id = $resa->reservation_id;
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->est_resa_club = $resa->est_reservation_club;

                    // Informations sur l'instructeur
                    if ($resa->id_instructeur != '') {
                        $planning[$avion->avion_id]->reservations[$jourCode][$h]->instructeur = $liste_instructeurs[$resa->id_instructeur]->nom . ' (' . $liste_instructeurs[$resa->id_instructeur]->code . ')';
                    }
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->infobulle = $resa->nom . (strlen(trim($resa->no_portable)) > 0 ? "<br/><img src='picts/mobile-phone.png' title='N° de téléphone portable'> " . $resa->no_portable : "") . (strlen(trim($resa->destination)) > 0 ? "<br/><img src='picts/destination.png' title='Destination du vol'> " . $resa->destination : "") . (strlen(trim($resa->commentaires)) > 0 ? "<br/><img src='picts/comment.png' title='Commentaires sur la réservation'> <i>" . $resa->commentaires . "</i>" : "")
                            . ($resa->id_instructeur != '' ? "<br/><img src='picts/instructeur.png'> " . $planning[$avion->avion_id]->reservations[$jourCode][$h]->instructeur : "");

                    // Calcul du rowspan
                    // 1 case = 30 min
                    // donc rowspan = (heures * 60 + minutes) / 30
                    $d_debut = new DateTime($resa->heure_debut);
                    $d_fin = new DateTime($resa->heure_fin);
                    $diff = $d_debut->diff($d_fin);
                    $planning[$avion->avion_id]->reservations [$jourCode] [$h]->rowspan = (intval($diff->format('%h')) * 60 + intval($diff->format('%i'))) / 30;
                }
                // Si la date de la case est entre la date de début et de fin de la résa
                // on n'affiche pas la case (à cause du rowspan)
                if (date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' > $resa->heure_debut && date('Y-m-d', strtotime($jourCode)) . ' ' . $h . ':00' < $resa->heure_fin) {
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->libre = false;
                    $planning[$avion->avion_id]->reservations[$jourCode][$h]->masquer = true;
                }
            }
        }
    }
}

$tpl->assign('page_title', _T("PLANNING.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

// Avions
$tpl->assign('liste_avions', $liste_avions);

// Entête calendrier / Semaine choisie
$tpl->assign('jours', $jours);
$tpl->assign('tooltip_jours', $tooltip_jours);
$tpl->assign('semaine_avant', date('Ymd', strtotime('-3 days', strtotime($depart))));
$tpl->assign('semaine_apres', date('Ymd', strtotime('+7 days', strtotime($depart))));
$tpl->assign('numero_semaine', date('W', strtotime($depart)));
$tpl->assign('annee', date('Y', strtotime($depart)));
$tpl->assign('debut_semaine', date('d/m/Y', strtotime($depart)));
$tpl->assign('fin_semaine', date('d/m/Y', strtotime('+6 days', strtotime($depart))));
$tpl->assign('require_calendar', true);
$tpl->assign('jour_selectionne', $jour_selectionne);
$tpl->assign('jour_selectionne_IHM', date('d/m/Y', strtotime($jour_selectionne)));

// Couleurs des paramètres
$tpl->assign('couleur_libre', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE));
$tpl->assign('couleur_libre_clair', PiloteParametre::eclaircirCouleurHexa(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE)));
$tpl->assign('couleur_libre_nuit', PiloteParametre::assombrirCouleur(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_LIBRE)));
$tpl->assign('couleur_reserve', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_RESERVE));
$tpl->assign('couleur_interdit', PiloteParametre::getValeurParametre(PiloteParametre:: PARAM_COULEUR_INTERDIT));
$tpl->assign('couleur_interdit_nuit', PiloteParametre::assombrirCouleur(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COULEUR_INTERDIT)));

// Calendrier principal : heures et résas
$tpl->assign('planning', $planning);
$tpl->assign('heures', $heures);

// Messages informatifs
$tpl->assign('resa_ok', $resa_ok);
$tpl->assign('resa_annule', $resa_annule);
$tpl->assign('ajax', $ajax);

if ($ajax) {
    $tpl->display('planning.tpl', PILOTE_SMARTY_PREFIX);
} else {
    $content = $tpl->fetch('planning.tpl', PILOTE_SMARTY_PREFIX);
    $tpl->assign('content', $content);
    //Set path to main Galette's template
    $tpl->template_dir = $orig_template_path;
    $tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
}
