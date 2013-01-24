<?php

/**
 * Change password for a member
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

// Variable globale qui sert à atteindre la base de donnée et exécuter les requêtes
global $zdb;

$liste_adherents = array();
$mot_passe_change = false;
$erreurs = false;
$liste_erreurs = array();

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
        $liste_adherents[$row->id_adh] = $row->nom_adh . ' ' . $row->prenom_adh . ' (' . $row->login_adh . ')';
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

/**
 * Traitement du reset de mot de passe
 */
if (array_key_exists('adherent_id', $_POST)) {
    $adherent = new Galette\Entity\Adherent(intval($_POST['adherent_id']));

    if (strlen($_POST['mot_de_passe']) < 6) {
        $erreurs = true;
        $liste_erreurs[] = _T('CHANGE PWD.TROP COURT');
    }
    if (strlen($_POST['mot_de_passe']) > 12) {
        $erreurs = true;
        $liste_erreurs[] = _T('CHANGE PWD.TROP LONG');
    }
    if (preg_match('/[0-9]/', $_POST['mot_de_passe']) == 0) {
        $erreurs = true;
        $liste_erreurs[] = _T('CHANGE PWD.MANQUE CHIFFRE');
    }
    if (preg_match('/[a-zA-Z]/', $_POST['mot_de_passe']) == 0) {
        $erreurs = true;
        $liste_erreurs[] = _T('CHANGE PWD.MANQUE LETTRE');
    }
    if ($_POST['mot_de_passe'] != $_POST['confirm_mot_de_passe']) {
        $erreurs = true;
        $liste_erreurs[] = _T('CHANGE PWD.CONFIRMATION DIFFERENT');
    }

    if (!$erreurs) {
        try {
            $zdb->db->update(PREFIX_DB . Galette\Entity\Adherent::TABLE, array('mdp_adh' => md5($_POST['mot_de_passe'])), Galette\Entity\Adherent::PK . '=' . $adherent->id);
            $mot_passe_change = true;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            $erreurs = true;
            $liste_erreurs[] = 'Erreur lors de la mise à jour du mot de passe';
        }
    }
}

$tpl->assign('page_title', _T("CHANGE PWD.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('mot_passe_change', $mot_passe_change);
$tpl->assign('erreurs', $erreurs);
$tpl->assign('liste_erreurs', $liste_erreurs);
if (isset($adherent)) {
    $tpl->assign('select_adherent_id', $adherent->id);
}

$content = $tpl->fetch('change_pwd.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
