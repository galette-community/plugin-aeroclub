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

$pseudo = $login->login;
if ($login->isAdmin() && array_key_exists('login_adherent', $_GET)) {
    $pseudo = $_GET['login_adherent'];
}

$liste_adherents = array();
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
        $liste_adherents[$row->login_adh] = $row->nom_adh . ' ' . $row->prenom_adh . ' (' . $row->login_adh . ')';
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}


$tpl->assign('page_title', _T("GRAPHIQUE.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('adherent_selectionne', $pseudo);

$content = $tpl->fetch('graphique.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
