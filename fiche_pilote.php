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
if (!$login->isLogged()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

$tpl->assign('page_title', _T("FICHE PILOTE.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

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

/**
 * Récupération du dernier vol du pilote
 */
global $zdb;

try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'max(date_operation) as dernier_vol')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('duree_minute is not null');
    if ($select->query()->rowCount() == 1) {
        $dt = new DateTime($select->query()->fetch()->dernier_vol);
        $tpl->assign('dernier_vol', $dt->format('j M Y'));
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

$adh = new Galette\Entity\Adherent($pseudo);
$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('adherent_selectionne', $pseudo);
$tpl->assign('adherent', $adh);
$tpl->assign('complement', new PiloteAdherentComplement($pseudo));

$content = $tpl->fetch('fiche_pilote.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
