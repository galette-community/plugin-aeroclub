<?php

/**
 * Display a form to edit / add a flight teacher
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
 * Annulation de l'enregistrement, on revient à la liste
 */
if (array_key_exists('annuler', $_POST)) {
    header('Location: liste_instructeurs.php?msg=pas_enregistre');
}

/**
 * Enregistrement des modifications
 */
if (array_key_exists('modifier', $_POST)) {
    $instructeur = new PiloteInstructeur(intval($_POST['instructeur_id']));
    $instructeur->code = $_POST['code'];
    $instructeur->nom = $_POST['nom'];
    $instructeur->code_adherent = $_POST['code_adherent'];
    if ($_POST['adherent_id'] == 'null') {
        $instructeur->adherent_id = new Zend_Db_Expr('NULL');
    } else {
        $instructeur->adherent_id = $_POST['adherent_id'];
    }
    $instructeur->store();
    header('Location: liste_instructeurs.php?msg=enregistre');
}

// Variable globale qui sert à atteindre la base de donnée et exécuter les requêtes
global $zdb;

/**
 * Lecture des infos de l'instructeur
 */
if (array_key_exists('instructeur_id', $_GET)) {
    $instructeur = new PiloteInstructeur(intval($_GET['instructeur_id']));
} else {
    $instructeur = new PiloteInstructeur();
}

$liste_adherents = PiloteOperation::getAdherentsActifs();

$tpl->assign('page_title', _T("MODIFIER INSTRUCTEUR.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('instructeur', $instructeur);
$tpl->assign('liste_adherents', $liste_adherents);

$content = $tpl->fetch('modifier_instructeur.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
