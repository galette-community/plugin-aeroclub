<?php

/**
 * Special import function from an very specific ACCESS 2000 program.
 * Very specific and only usable with this ACCESS 2000 program.
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
 * Transforme une date au format Access 2 en date au format string classique ou string SQL
 * 
 * @param string $str Date à traduire
 * @param bool $sql Indique si la date doit être formatée au format SQL 'AAAA-MM-JJ' ou standard 'JJ/MM/AAAA'
 * 
 * @return La date au bon format
 */
function dateAccessToString($str, $sql = false) {
    if (strlen($str) < 4) {
        return false;
    }

    $dt = date_create_from_format("d/m/Y H:i:s", $str);
    if ($dt == FALSE) {
        return false;
    }
    // L'export se fait désormais avec une date sur 4 chiffres. 
    // Plus la peine de vérifier la cohérence de date
    //if (intval($dt->format("Y")) > 2040) {
    //    $dt = $dt->sub(new DateInterval('P100Y'));
    //}
    if ($sql) {
        return $dt->format("Y-m-d");
    } else {
        return $dt->format("d/m/Y");
    }
}

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
if (!$login->isLogged() || !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

$historique = array();
if (is_dir('historique_import')) {
    $dh = opendir('./historique_import');
    while (false !== ($filename = readdir($dh))) {
        if (preg_match('/.txt$/i', $filename)) {
            $impt = new stdClass();
            $impt->name = $filename;
            $impt->date = date("d M Y à H:i", filemtime('historique_import/' . $filename));
            $impt->size = '' . round(filesize('historique_import/' . $filename) / 1024, 0) . ' ko';
            $impt->type = filetype('historique_import/' . $filename);
            $f = fopen('historique_import/' . $filename, 'r');
            $nb = 0;
            $datemaj = '1980-01-01';
            while (($line = fgetcsv($f, 1000, ';', '"')) !== FALSE) {
                $nb++;
                if ($line[0] == 'MEMBRES' || $line[0] == 'OPERATIONS') {
                    $dt = dateAccessToString($line[count($line) - 1], true);
                    if ($dt && $dt > $datemaj) {
                        $datemaj = $dt;
                    }
                }
            }
            fclose($f);
            $impt->lines = $nb;
            $impt->datemaj = date('d M Y', strtotime($datemaj));
            $historique[] = $impt;
        }
    }
}

$tpl->assign('page_title', _T("HISTO IMPORT.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('historique', $historique);

$content = $tpl->fetch('histo_import.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
