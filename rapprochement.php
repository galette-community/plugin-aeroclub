<?php

/**
 * Delete a plane
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

global $zdb;

$nb_operations = 0;
if (array_key_exists('sauver', $_POST)) {
    $resas = $_POST['resas'];
    foreach ($resas as $resa_id) {
        if ($_POST['rapproche_' . $resa_id] == '1') {
            $reservation = new PiloteReservation(intval($resa_id));

            $operation = new PiloteOperation();
            $operation->id_adherent = $_POST['id_adherent_' . $resa_id];
            $operation->access_exercice = date('Y', strtotime($reservation->heure_debut));
            $operation->access_id = PiloteOperation::getNextAccessId(intval($operation->access_exercice));
            $operation->date_operation = date('Y-m-d', strtotime($reservation->heure_debut));
            $operation->immatriculation = $_POST['avion_' . $resa_id];
            $operation->duree_minute = intval($_POST['heure_' . $resa_id]) * 60 + intval($_POST['minute_' . $resa_id]);
            $operation->type_vol = $_POST['type_vol_' . $resa_id] == '-null-' ? $_POST['type_vol_text_' . $resa_id] : $_POST['type_vol_' . $resa_id];
            $operation->aeroport_depart = $_POST['depart_' . $resa_id];
            $operation->aeroport_arrivee = $_POST['arrivee_' . $resa_id];
            $operation->instructeur = $_POST['instructeur_' . $resa_id];
            $operation->nb_atterrissages = $_POST['atterissages_' . $resa_id];
            $operation->type_operation = 'Vol';
            $operation->libelle_operation = $_POST['libelle_' . $resa_id];
            $operation->montant_operation = doubleval(str_replace(',', '.', $_POST['montant_' . $resa_id]));
            $operation->nb_passagers = $_POST['passagers_' . $resa_id];

            $operation->store();

            $reservation->est_rapproche = true;
            $reservation->id_operation = $operation->operation_id;
            $reservation->store();

            $nb_operations++;
        }
    }
}

/**
 * Récupération de la liste des instructeurs
 */
$liste_instructeurs = PiloteInstructeur::getTousInstructeurs('nom', 'asc', 1, 9999);

/**
 * Récupération de la liste des avions
 */
$liste_avions = PiloteAvion::getTousAvionsReservables();

/**
 * Récupération des rapprochements
 */
$liste_rapprochements = PiloteReservation::getReservationsNonRapprochees();
foreach ($liste_rapprochements as $resa_id => $resa) {
    $resa->heure_debut_IHM = date('D j M Y', strtotime($resa->heure_debut));
    $resa->heure_debut_IHM .= '<br/> &nbsp; &nbsp; de ' . date('H:i', strtotime($resa->heure_debut));
    $resa->heure_debut_IHM .= ' à ' . date('H:i', strtotime($resa->heure_fin));

    $debut = new DateTime($resa->heure_debut);
    $fin = new DateTime($resa->heure_fin);
    $diff = $debut->diff($fin);
    $resa->minute = $diff->i;
    $resa->heure = $diff->h;
    $resa->libelle = $liste_avions[$resa->id_avion]->nom . ' '
            . $liste_avions[$resa->id_avion]->immatriculation
            . ' - ' . ($resa->heure < 10 ? '0' : '') . $resa->heure
            . ':' . ($resa->minute < 10 ? '0' : '') . $resa->minute
            . ' * ' . $liste_avions[$resa->id_avion]->cout_horaire;
}

/**
 * Récupération des différents types de vol
 */
$liste_type_vols = array();
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'type_vol')
            ->order('type_vol')
            ->group('type_vol');
    $rows = $select->query()->fetchAll();
    foreach ($rows as $row) {
        $liste_type_vols[] = $row->type_vol;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}


/**
 * Récupération de la liste des pilotes adhérents actifs
 */
$liste_adherents = array();
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

$tpl->assign('page_title', _T("RAPPROCHEMENT.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_rapprochements', $liste_rapprochements);
$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('liste_avions', $liste_avions);
$tpl->assign('liste_instructeurs', $liste_instructeurs);
$tpl->assign('liste_type_vols', $liste_type_vols);
$tpl->assign('depart', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_CODE_AEROCLUB));
$tpl->assign('nb_operations', $nb_operations);

$content = $tpl->fetch('rapprochement.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
