<?php

/**
 * Display informations about the connected pilot
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

// Import des classes de notre plugin
require_once '_config.inc.php';

if (!$login->isLogged() || !(PiloteInstructeur::isPiloteInstructeur($login->login) || $login->isAdmin() || $login->isStaff())) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Récupération du tri
$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'nom_adh';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
$annee = array_key_exists('annee', $_GET) ? $_GET['annee'] : date('Y');
$selection_type_vols = array_key_exists('selection_type_vols', $_GET) ? $_GET['selection_type_vols'] : array();

$tpl->assign('page_title', _T("TYPES VOLS PILOTES.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

// Récupération des données en base
$stats = PiloteOperation::getTypeVolsHeuresPilotes($annee, $tri, $direction);
$tvols = PiloteOperation::getTypesVols($annee);
$annees = PiloteOperation::getAnneesOperations();

$types_vols = array();
$sommes = array();
for ($i = 0; $i < count($tvols); $i++) {
    $types_vols['TV' . $i] = $tvols[$i];
    $sommes['TV' . $i] = 0;
}
$types_vols['TVtotal'] = 'Total';
$sommes['TVtotal'] = 0;

// Mise en page des données
for ($i = 0; $i < count($stats); $i++) {
    foreach ($types_vols as $k => $v) {
        if (!is_null($stats[$i]->$k)) {
            // Total
            $sommes[$k] += $stats[$i]->$k;
            // Mise en forme 
            $nb_h = floor($stats[$i]->$k / 60);
            $nb_m = $stats[$i]->$k - $nb_h * 60;
            $stats[$i]->$k = ($nb_h > 0 ? $nb_h . 'h' : '') . ($nb_m < 10 ? '0' : '') . $nb_m . 'min';
        } else {
            $stats[$i]->$k = '';
        }
    }
}

// Suppression des pilotes qui n'ont pas de données quand 1 seul type_vol sélectionné
if (count($selection_type_vols) == 1) {
    $k = $selection_type_vols[0];
    $n = count($stats);
    for ($i = 0; $i < $n; $i++) {
        if (strlen($stats[$i]->$k) <= 1) {
            unset($stats[$i]);
        }
    }
}

// Ajout ligne totaux
$objTotal = new stdClass();
$objTotal->nom_adh = '<b>Totaux</b>';
// Objet de sélection
$selection = new stdClass();
foreach ($types_vols as $k => $v) {
    // Mise en forme 
    $nb_h = floor($sommes[$k] / 60);
    $nb_m = $sommes[$k] - $nb_h * 60;
    $objTotal->$k = '<b>' . ($nb_h > 0 ? $nb_h . 'h' : '') . ($nb_m < 10 ? '0' : '') . $nb_m . 'min</b>';
    // Affichage des colonnes
    $selection->$k = in_array($k, $selection_type_vols);
}
$stats[] = $objTotal;

$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('stats', $stats);
$tpl->assign('types_vols', $types_vols);
$tpl->assign('annees', $annees);
$tpl->assign('sel_annee', $annee);
$tpl->assign('selection', $selection);

$content = $tpl->fetch('types_vols_pilotes.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
