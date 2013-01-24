<?php

/**
 * List all disponibilities for a plane and allow to edit/add a disponibility.
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
if (!$login->isLogged() || !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

$avion_id = array_key_exists('avion_id', $_GET) ? $_GET['avion_id'] : $_POST['avion_id'];

/**
 * Sauvegarde des modifs
 */
$nb_dispo_maj = 0;
if (array_key_exists('valider', $_POST)) {
    $dispos = $_POST['dispos'];
    foreach ($dispos as $dispo_id) {
        $dispo = new PiloteAvionDispo(intval($dispo_id));
        list($j, $m, $a) = split('/', $_POST['date_debut_' . $dispo_id]);
        $dispo->date_debut = $a . '-' . $m . '-' . $j;
        if (strlen($_POST['date_fin_' . $dispo_id]) > 0) {
            list($j, $m, $a) = split('/', $_POST['date_fin_' . $dispo_id]);
            $dispo->date_fin = $a . '-' . $m . '-' . $j;
        }
        $dispo->store();
        $nb_dispo_maj++;
    }

    if (strlen($_POST['date_debut_nouveau']) > 0) {
        $dispo = new PiloteAvionDispo();
        $dispo->avion_id = $avion_id;
        list($j, $m, $a) = split('/', $_POST['date_debut_nouveau']);
        $dispo->date_debut = $a . '-' . $m . '-' . $j;
        if (strlen($_POST['date_fin_nouveau']) > 0) {
            list($j, $m, $a) = split('/', $_POST['date_fin_nouveau']);
            $dispo->date_fin = $a . '-' . $m . '-' . $j;
        }
        $dispo->store();
        $nb_dispo_maj++;
    }
}

/**
 * Annulation des modifs, retour à la liste
 */
if (array_key_exists('retour', $_POST)) {
    header('Location: liste_avions.php');
}

/**
 * Récupération des dispos pour l'avion
 */
$liste_dispos = PiloteAvionDispo::getDisponibilitesPourAvion(intval($avion_id));

$tpl->assign('page_title', _T("DISPO.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_dispos', $liste_dispos);
$tpl->assign('require_calendar', true);
$tpl->assign('nb_maj', $nb_dispo_maj);
$tpl->assign('avion_id', $avion_id);
$tpl->assign('avion', new PiloteAvion(intval($avion_id)));

$content = $tpl->fetch('dispo.tpl', PILOTE_SMARTY_PREFIX);

$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
