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

// Si clic sur le bouton Sendmail
if (array_key_exists('sendmail', $_GET)) {
    // Import de la classe nécessaire
    require_once GALETTE_BASE_PATH . 'classes/varslist.class.php';

    // On efface la variable globale de mailing pour qu'il prenne en compte les
    // identifiants sélectionnés
    $_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['mailing'] = null;
    unset($_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['mailing']);
    
    // Récupération de la liste des membres sélectionnés
    if (isset($_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['varslist'])) {
        $varslist = unserialize($_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['varslist']);
    } else {
        $varslist = new VarsList();
    }
    $varslist->selected = $_GET['member_sel'];
    // Sauvegarde de la liste des membres sélectionnés
    $_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['caller'] = 'plugins/Pilote/soldes_pilotes.php';
    $_SESSION['galette'][PREFIX_DB . '_' . NAME_DB]['varslist'] = serialize($varslist);
    // Redirection vers la page de mailing de Galette
    header('location: ' . GALETTE_BASE_PATH . 'mailing_adherents.php');
}

$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'nom';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
$actif = array_key_exists('actif', $_GET) ? $_GET['actif'] : 'null';
$solde = array_key_exists('solde', $_GET) ? $_GET['solde'] : 'all';

switch ($tri) {
    case 'nom':
        $tri_sql = 'nom_adh';
        break;
    case 'prenom':
        $tri_sql = 'prenom_adh';
        break;
    case 'pseudo':
        $tri_sql = 'login_adh';
        break;
    case 'email':
        $tri_sql = 'email_adh';
        break;
    case 'solde':
        $tri_sql = 'solde';
        break;
}

$tpl->assign('page_title', _T("SOLDES PILOTES.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('actif', $actif);
$tpl->assign('solde', $solde);
$a = null;
if ($actif == 'yes') {
    $a = true;
} else if ($actif == 'no') {
    $a = false;
}

$tpl->assign('soldes', PiloteOperation::getSoldesPilotes($a, $solde, $tri_sql, $direction));

$content = $tpl->fetch('soldes_pilotes.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
