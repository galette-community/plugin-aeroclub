<?php

/**
 * List all known operations for a pilot.
 * An admin can see operations for an other pilote.
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
$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'date';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
$annee_selectionnee = array_key_exists('compte_annee', $_GET) ? $_GET['compte_annee'] : date('Y');
$page = array_key_exists('page', $_GET) ? $_GET['page'] : 1;
$nb_lignes = array_key_exists('nb_lignes', $_GET) ? $_GET['nb_lignes'] : $preferences->pref_numrows;
$pseudo = $login->login;
$complement = '';
if ($login->isAdmin() && array_key_exists('login_adherent', $_GET)) {
    $pseudo = $_GET['login_adherent'];
    $complement = '&login_adherent=' . $pseudo;
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
    case 'type':
        $tri_colonne = 'type_operation';
        break;
    case 'libelle':
        $tri_colonne = 'libelle_operation';
        break;
    case'duree':
        $tri_colonne = 'duree_minute';
        break;
    case'montant':
        $tri_colonne = 'montant_operation';
        break;
}

if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $liste_operations = PiloteOperation::getOperationsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes, intval($annee_selectionnee));
    $nb_operations = PiloteOperation::getNombreOperationsForLogin($pseudo, intval($annee_selectionnee));
} else {
    $liste_operations = PiloteOperation::getOperationsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes);
    $nb_operations = PiloteOperation::getNombreOperationsForLogin($pseudo);
}

/**
 * Calcul de la pagination
 */
$pagination = PiloteParametre::faitPagination($page, $tri, $direction, $nb_operations, $nb_lignes, '&compte_annee=' . $annee_selectionnee . $complement);

$total_vols = 0;
$solde = 0;
$liste_annees = array();

/**
 * Ajout des années disponibles à la consultation dans la liste déroulante
 */
$liste_annees = PiloteOperation::getAnneesOperations();
// Insert à la position 0
array_splice($liste_annees, 0, 0, array(_T("COMPTE VOL.TOUTES")));

$liste_adherents = PiloteOperation::getAdherentsActifs();

/**
 * Calcul des totaux : solde + heures de vol
 */
// Correction BUG 3
if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $toutes_operations = PiloteOperation::getOperationsForLogin($pseudo, 'date_operation', 'asc', 1, 99999, intval($annee_selectionnee));
} else {
    $toutes_operations = PiloteOperation::getOperationsForLogin($pseudo, 'date_operation', 'asc', 1, 9999);
}
foreach ($toutes_operations as $operation) {
    $solde += $operation->montant_operation;
    $total_vols += $operation->duree_minute;
}

$tpl->assign('page_title', _T("COMPTE VOL.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_operations', $liste_operations);
// Date du jour
$tpl->assign('date_jour', $toutes_operations[count($toutes_operations) - 1]->date_operation);
// Total vols
$heure_vols = floor($total_vols / 60);
$minute_vols = $total_vols - $heure_vols * 60;
if ($heure_vols > 0) {
    $tpl->assign('total_vols', $heure_vols . ' h ' . $minute_vols . ' min');
} else {
    $tpl->assign('total_vols', $minute_vols . ' min');
}
// Solde
$tpl->assign('solde', number_format($solde, 2, ',', ' ') . ' EUR');
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

$tpl->assign('enregistre', array_key_exists('msg', $_GET) && $_GET['msg'] == 'ok');
$tpl->assign('pas_enregistre', array_key_exists('msg', $_GET) && $_GET['msg'] == 'annule');

$content = $tpl->fetch('compte_vol.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
