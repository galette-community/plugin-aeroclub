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

$selection = new stdClass();
if (array_key_exists('sel_annee', $_POST)) {
    for ($i = 0; $i < count($_POST['sel_annee']); $i++) {
        if ($i < 3) {
            $a = $_POST['sel_annee'][$i];
            $selection->$a = true;
        }
    }
}
$sel_immat = array_key_exists('immatriculation', $_POST) ? $_POST['immatriculation'] : '';

$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'avion';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
switch ($tri) {
    case 'avion':
        $tri_sql = 'immat';
        break;
    case 'an-1':
        $tri_sql = 'somme2011';
        break;
    case 'an':
        $tri_sql = 'somme2012';
        break;
    case 'glissant':
        $tri_sql = 'sommeglissant';
        break;
}

$tpl->assign('page_title', _T("HEURES AVION.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$avion = array();
$stats = PiloteOperation::getStatistiquesAvions($tri_sql, $direction);
foreach ($stats as $ligne) {
    $obj = new stdClass();
    $obj->avion = $ligne->immat;

    if (!is_null($ligne->somme2011)) {
        $nb_h = floor($ligne->somme2011 / 60);
        $nb_m = $ligne->somme2011 - $nb_h * 60;
        $obj->somme2011 = ($nb_h > 0 ? $nb_h . 'h ' : '') . $nb_m . 'min';
    } else {
        $obj->somme2011 = '';
    }
    if (!is_null($ligne->somme2012)) {
        $nb_h = floor($ligne->somme2012 / 60);
        $nb_m = $ligne->somme2012 - $nb_h * 60;
        $obj->somme2012 = ($nb_h > 0 ? $nb_h . 'h ' : '') . $nb_m . 'min';
    } else {
        $obj->somme2012 = '';
    }
    if (!is_null($ligne->sommeglissant)) {
        $nb_h = floor($ligne->sommeglissant / 60);
        $nb_m = $ligne->sommeglissant - $nb_h * 60;
        $obj->sommeglissant = ($nb_h > 0 ? $nb_h . 'h ' : '') . $nb_m . 'min';
    } else {
        $obj->sommeglissant = '';
    }
    $avion[] = $obj;
}

$annees = PiloteOperation::getAnneesOperations();
$avions_actifs = PiloteOperation::getAvionsActifs();

$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('avions', $avion);
$tpl->assign('annee_derniere', date('Y', strtotime('-1 years')));
$tpl->assign('cette_annee', date('Y'));
$tpl->assign('an_glissant', date('d/m/Y', strtotime('-1 years')));
$tpl->assign('annees', $annees);
$tpl->assign('selection', $selection);
$tpl->assign('avions_actifs', $avions_actifs);
$tpl->assign('sel_immat', $sel_immat);
$tpl->assign('require_tabs', true);
$tpl->assign('sel_tab', $sel_immat == '' ? '' : '{ selected: 2 }');

$content = $tpl->fetch('heures_avion.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
