<?php

/**
 * Display all parameters of the Plugin and allow the Admin to edit
 * the values of the parameters
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

$tpl->assign('page_title', _T("VERSION.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$lignes = array();
if (file_exists('changelog.txt')) {
    $f = fopen('changelog.txt', 'r');
    $ul_start = false;
    while (($line = fgets($f)) !== FALSE) {
        $line = trim($line);
        if (substr($line, 0, 2) == "--" && substr($line, -2) == "--") {
            $line = '<h3>' . substr($line, 2, strlen($line) - 4) . '</h3>';
        } else if (substr($line, 0, 2) == "- ") {
            $line = ($ul_start ? '' : "<ul>\n") . '<li>' . substr($line, 1) . '</li>';
            $ul_start = true;
        } else {
            $line = ($ul_start ? "</ul>\n" : '') . '<br/>' . $line;
            $ul_start = false;
        }
        $lignes[] = $line;
    }
    fclose($f);
}

$tpl->assign('lignes', $lignes);

$content = $tpl->fetch('version.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
