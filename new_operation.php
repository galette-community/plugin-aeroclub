<?php

/**
 * Display a form to edit or add a new operation.
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

$enregistrer = false;
$annuler = false;

if (array_key_exists('sauver', $_POST)) {
    if (strlen($_POST['operation_id'] > 0)) {
        $operation = new PiloteOperation(intval($_POST['operation_id']));
    } else {
        $operation = new PiloteOperation();
    }
    $operation->id_adherent = $_POST['adherent_id'];
    $operation->access_exercice = $_POST['exercice'];
    if ($operation->access_id == '') {
        $operation->access_id = PiloteOperation::getNextAccessId(intval($operation->access_exercice));
    }
    list($j, $m, $a) = explode('/', $_POST['date_operation']);
    $operation->date_operation = $a . '-' . $m . '-' . $j;
    $operation->type_operation = $_POST['sel_type_operation'] == '-null-' ? $_POST['autre_type_operation'] : $_POST['sel_type_operation'];
    $operation->libelle_operation = $_POST['libelle_operation'];
    $operation->montant_operation = doubleval(str_replace(',', '.', $_POST['montant']));

    if (strtolower($operation->type_operation) == 'vol') {
        $operation->immatriculation = $_POST['immatriculation'];
        $operation->duree_minute = intval($_POST['heures']) * 60 + intval($_POST['minutes']);
        $operation->type_vol = $_POST['sel_type_vol'] == '-null-' ? $_POST['autre_type_vol'] : $_POST['sel_type_vol'];
        $operation->aeroport_depart = $_POST['depart'];
        $operation->aeroport_arrivee = $_POST['arrivee'];
        $operation->instructeur = $_POST['instructeur'];
        $operation->nb_atterrissages = intval($_POST['atterissage']);
        $operation->nb_passagers = intval($_POST['nb_passagers']);
    } else {
        $operation->immatriculation = '*';
        $operation->duree_minute = new Zend_Db_Expr('NULL');
        $operation->type_vol = '';
        $operation->aeroport_depart = '';
        $operation->aeroport_arrivee = '';
        $operation->instructeur = '';
        $operation->nb_atterrissages = 0;
    }

    $operation->store();
    $enregistrer = true;

    if ($_POST['origine'] == 'compte_vol') {
        $adherent = new Galette\Entity\Adherent(intval($operation->id_adherent));
        header('Location: compte_vol.php?msg=ok&nb_lignes=20&compte_annee=' . $operation->access_exercice . '&login_adherent=' . $adherent->login);
    } else if ($_POST['origine'] == 'liste_vols') {
        $adherent = new Galette\Entity\Adherent(intval($operation->id_adherent));
        header('Location: liste_vols.php?msg=ok&nb_lignes=20&compte_annee=' . $operation->access_exercice . '&login_adherent=' . $adherent->login);
    }
}

if (array_key_exists('annuler', $_POST)) {
    $annuler = true;

    if ($_POST['origine'] == 'compte_vol') {
        $adherent = new Galette\Entity\Adherent(intval($_POST['adherent_id']));
        header('Location: compte_vol.php?msg=annule&nb_lignes=20&compte_annee=' . $_POST['exercice'] . '&login_adherent=' . $adherent->login);
    } else if ($_POST['origine'] == 'liste_vols') {
        $adherent = new Galette\Entity\Adherent(intval($_POST['adherent_id']));
        header('Location: liste_vols.php?msg=annule&nb_lignes=20&compte_annee=' . $_POST['exercice'] . '&login_adherent=' . $adherent->login);
    }
}

global $zdb;

$liste_type_operations = array();
/**
 * Récupération de la liste des types d'opérations
 */
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'type_operation')
            ->group('type_operation')
            ->order('1');
    $result = $select->query()->fetchAll();
    foreach ($result as $row) {
        $liste_type_operations[] = $row->type_operation;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

$liste_type_vols = array();
/**
 * Récupération de la liste des types de vols
 */
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'type_vol')
            ->group('type_vol')
            ->order('1');
    $result = $select->query()->fetchAll();
    foreach ($result as $row) {
        $liste_type_vols[] = $row->type_vol;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

/**
 * Récupération de l'opération si elle existe ou initialisation d'une nouvelle
 */
if (array_key_exists('operation_id', $_GET)) {
    $operation = new PiloteOperation(intval($_GET['operation_id']));
    $operation->duree_heure = floor($operation->duree_minute / 60);
    $operation->duree_minute = $operation->duree_minute - $operation->duree_heure * 60;
    $operation->date_operation_IHM = date('d/m/Y', strtotime($operation->date_operation));
    $operation->montant_operation = number_format($operation->montant_operation, 2);
} else {
    $operation = new PiloteOperation();
    $operation->access_exercice = date('Y');
    $operation->date_operation_IHM = date('d/m/Y');
    $operation->aeroport_depart = PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CODE_AEROCLUB);
    $operation->aeroport_arrivee = PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CODE_AEROCLUB);
    $operation->nb_atterrissages = 1;
    $operation->nb_passagers = 0;
    $operation->duree_minute = 0;
    $operation->duree_heure = 0;
}

$liste_adherents = array();
$login_adherent = '';
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
        if ($operation->id_adherent == $row->id_adh) {
            $login_adherent = $row->login_adh;
        }
        $liste_adherents[$row->id_adh] = $row->nom_adh . ' ' . $row->prenom_adh . ' (' . $row->login_adh . ')';
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}


$tpl->assign('page_title', _T("NEW OPERATION.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('liste_type_operations', $liste_type_operations);
$tpl->assign('liste_avions', PiloteAvion::getTousAvionsReservables());
$tpl->assign('liste_type_vols', $liste_type_vols);
$tpl->assign('liste_instructeurs', PiloteInstructeur::getTousInstructeurs('nom', 'asc', 1, 9999));
$tpl->assign('require_calendar', true);
$tpl->assign('origine', $_GET['origine']);
$tpl->assign('login_adherent', $login_adherent);

$tpl->assign('operation', $operation);

$tpl->assign('enregistre', $enregistrer);
$tpl->assign('pas_enregistre', $annuler);

$content = $tpl->fetch('new_operation.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
