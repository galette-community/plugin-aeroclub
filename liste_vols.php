<?php

/**
 * List all flights for the connected pilot.
 * An admin can see the fligts for an another pilot.
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
define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
if (!$login->isLogged()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

/**
 * Variables de tri + année sélectionnée
 */
$tri = filter_has_var(INPUT_GET, 'tri') ? filter_input(INPUT_GET, 'tri') : 'date';
$direction = filter_has_var(INPUT_GET, 'direction') ? filter_input(INPUT_GET, 'direction') : 'asc';
$annee_selectionnee = filter_has_var(INPUT_GET, 'compte_annee') ? filter_input(INPUT_GET, 'compte_annee') : date('Y');
$page = filter_has_var(INPUT_GET, 'page') ? filter_input(INPUT_GET, 'page') : 0;
$nb_lignes = filter_has_var(INPUT_GET, 'nb_lignes') ? filter_input(INPUT_GET, 'nb_lignes') : $preferences->pref_numrows;
$pseudo = $login->login;
$complement = '';
if ($login->isAdmin() && filter_has_var(INPUT_GET, 'login_adherent')) {
    $pseudo = filter_input(INPUT_GET, 'login_adherent');
    $complement = '&login_adherent=' . filter_input(INPUT_GET, 'login_adherent');
}

$liste_nb_lignes = array(10, 20, 30, 40, 50, 100, 150, 200, 300, 500);

/**
 * Définition de la colonne SQL triée
 */
$tri_colonne = '';
switch ($tri) {
    case 'date':
        $tri_colonne = 'date_operation';
        break;
    case 'immat':
        $tri_colonne = 'immatriculation';
        break;
    case 'typevol':
        $tri_colonne = 'type_vol';
        break;
    case 'aeroport':
        $tri_colonne = 'aeroport_depart';
        break;
    case'passagers':
        $tri_colonne = 'nb_passagers';
        break;
    case'instructeur':
        $tri_colonne = 'instructeur';
        break;
    case'nbatterr':
        $tri_colonne = 'nb_atterrissages';
        break;
    case'duree':
        $tri_colonne = 'duree_minute';
        break;
    case'montant':
        $tri_colonne = 'montant_operation';
        break;
}

if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $liste_operations = PiloteOperation::getVolsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes, intval($annee_selectionnee));
    $nb_operations = PiloteOperation::getNombreVolsForLogin($pseudo, intval($annee_selectionnee));
} else {
    $liste_operations = PiloteOperation::getVolsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes);
    $nb_operations = PiloteOperation::getNombreVolsForLogin($pseudo);
}

/**
 * Calcul de la pagination
 */
$pagination = PiloteParametre::faitPagination($page, $tri, $direction, $nb_operations, $nb_lignes, '&compte_annee=' . $annee_selectionnee . $complement);

/**
 * Ajout des années disponibles à la consultation dans la liste déroulante
 */
$liste_annees = PiloteOperation::getAnneesOperations();
// Insert à la position 0
array_splice($liste_annees, 0, 0, array(_T("LISTE VOLS.TOUTES")));

$liste_adherents = PiloteOperation::getAdherentsActifs();

$tpl->assign('page_title', _T("LISTE VOLS.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_operations', $liste_operations);
// Liste des années dispo
$tpl->assign('liste_annees', $liste_annees);
$tpl->assign('annee_selectionnee', $annee_selectionnee);
$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('pagination', $pagination);
$tpl->assign('nb_resultats', $nb_operations);
$tpl->assign('nb_lignes', $nb_lignes);
$tpl->assign('liste_nb_lignes', $liste_nb_lignes);
$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('adherent_selectionne', $pseudo);
$tpl->assign('complement', $complement);

$tpl->assign('enregistre', filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'ok');
$tpl->assign('pas_enregistre', filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'annule');

$content = $tpl->fetch('liste_vols.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
