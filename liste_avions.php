<?php

/**
 * List all known plane of the database.
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

/**
 * Variables de tri
 */
$tri = filter_has_var(INPUT_GET, 'tri') ? filter_input(INPUT_GET, 'tri') : 'nom';
$direction = filter_has_var(INPUT_GET, 'direction') ? filter_input(INPUT_GET, 'direction') : 'asc';
$page = filter_has_var(INPUT_GET, 'page') ? filter_input(INPUT_GET, 'page') : 0;

$liste_avions = PiloteAvion::getTousAvionsActifs($tri, $direction, $page, $preferences->pref_numrows);
$nb_avions = PiloteAvion::getNombreAvionsActifs();

$enregistre = filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'enregistre';
$pas_enregistre = filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'pas_enregistre';
$supprime = filter_has_var(INPUT_GET, 'msg') && filter_input(INPUT_GET, 'msg') == 'supprime';

/**
 * Calcul de la pagination
 */
$pagination = PiloteParametre::faitPagination($page, $tri, $direction, $nb_avions, $preferences->pref_numrows, '');

$tpl->assign('page_title', _T("LISTE AVIONS.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('pagination', $pagination);
$tpl->assign('nb_resultats', $nb_avions);
$tpl->assign('liste_avions', $liste_avions);

$tpl->assign('enregistre', $enregistre);
$tpl->assign('pas_enregistre', $pas_enregistre);
$tpl->assign('supprime', $supprime);

$content = $tpl->fetch('liste_avions.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
