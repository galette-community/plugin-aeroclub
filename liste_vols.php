<?php

/**
 * List all flights for the connected pilot.
 * An admin can see the fligts for an another pilot.
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

/**
 * Variables de tri + année sélectionnée
 */
$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'date';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
$annee_selectionnee = array_key_exists('compte_annee', $_GET) ? $_GET['compte_annee'] : date('Y');
$page = array_key_exists('page', $_GET) ? $_GET['page'] : 1;
$nb_lignes = array_key_exists('nb_lignes', $_GET) ? $_GET['nb_lignes'] : $preferences->pref_numrows;
$pseudo = $login->login;
$complement = '';
if ($login->isAdmin() && array_key_exists('login_adherent', $_GET)) {
    $pseudo = $_GET['login_adherent'];
    $complement = '&login_adherent=' . $pseudo;
}

$liste_nb_lignes = array(10, 20, 30, 40, 50, 100, 150, 200, 300, 500);

/**
 * Définition de la colonne SQL triée
 */
$tri_colonne = '';
switch ($tri) {
    case 'date':
        $tri_colonne = 'date_operation';
        break;
    case 'immat':
        $tri_colonne = 'immatriculation';
        break;
    case 'typevol':
        $tri_colonne = 'type_vol';
        break;
    case 'aeroport':
        $tri_colonne = 'aeroport_depart';
        break;
    case'passagers':
        $tri_colonne = 'nb_passagers';
        break;
    case'instructeur':
        $tri_colonne = 'instructeur';
        break;
    case'nbatterr':
        $tri_colonne = 'nb_atterrissages';
        break;
    case'duree':
        $tri_colonne = 'duree_minute';
        break;
    case'montant':
        $tri_colonne = 'montant_operation';
        break;
}

if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $liste_operations = PiloteOperation::getVolsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes, intval($annee_selectionnee));
    $nb_operations = PiloteOperation::getNombreVolsForLogin($pseudo, intval($annee_selectionnee));
} else {
    $liste_operations = PiloteOperation::getVolsForLogin($pseudo, $tri_colonne, $direction, $page, $nb_lignes);
    $nb_operations = PiloteOperation::getNombreVolsForLogin($pseudo);
}
$liste_annees = array();

/**
 * Calcul de la pagination
 */
$pagination = PiloteParametre::faitPagination($page, $tri, $direction, $nb_operations, $nb_lignes, '&compte_annee=' . $annee_selectionnee . $complement);

/**
 * Ajout des années disponibles à la consultation dans la liste déroulante
 */
$liste_annees[] = _T('LISTE VOLS.TOUTES');
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'YEAR(date_operation) as year')
            ->distinct()
            ->order('1');
    $rows = $select->query()->fetchAll();
    foreach ($rows as $annee) {
        $liste_annees[] = $annee->year;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
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

$tpl->assign('page_title', _T("LISTE VOLS.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_operations', $liste_operations);
// Liste des années dispo
$tpl->assign('liste_annees', $liste_annees);
$tpl->assign('annee_selectionnee', $annee_selectionnee);
$tpl->assign('tri', $tri);
$tpl->assign('direction', $direction);
$tpl->assign('pagination', $pagination);
$tpl->assign('nb_resultats', $nb_operations);
$tpl->assign('nb_lignes', $nb_lignes);
$tpl->assign('liste_nb_lignes', $liste_nb_lignes);
$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('adherent_selectionne', $pseudo);
$tpl->assign('complement', $complement);

$tpl->assign('enregistre', array_key_exists('msg', $_GET) && $_GET['msg'] == 'ok');
$tpl->assign('pas_enregistre', array_key_exists('msg', $_GET) && $_GET['msg'] == 'annule');

$content = $tpl->fetch('liste_vols.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
