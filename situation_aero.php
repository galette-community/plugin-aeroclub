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

global $zdb;

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
 * Calcul du solde du pilote
 */
$solde = 0;
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(montant_operation) as solde')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('YEAR(date_operation) = ?', date('Y'));
    if ($select->query()->rowCount() == 1) {
        $solde = $select->query()->fetch()->solde;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

/**
 * Calcul du temps de vol du pilote
 */
$duree_vol = 0;
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(duree_minute) as duree')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('duree_minute is not null')
            ->where('YEAR(date_operation) = ?', date('Y'));
    if ($select->query()->rowCount() == 1) {
        $duree_vol = $select->query()->fetch()->duree;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

/**
 * Calcul du temps de vol du pilote sur 1 année glissante
 */
$duree_vol_glissant = 0;
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(duree_minute) as duree')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('date_operation > ?', date('Y-m-d', strtotime('-1 year')));
    if ($select->query()->rowCount() == 1) {
        $duree_vol_glissant = $select->query()->fetch()->duree;
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

/**
 * Calcul du nombre d'atterrissages dans les 3 derniers mois
 */
$nb_atterr = 0;
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(nb_atterrissages) as sum')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('duree_minute is not null')
            ->where('date_operation > date_sub(now(), interval 3 month)');
    if ($select->query()->rowCount() == 1) {
        $nb_atterr = $select->query()->fetch()->sum;
        if ($nb_atterr == null) {
            $nb_atterr = 0;
        }
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
$dernier_vol = '';
try {
    $select = new Zend_Db_Select($zdb->db);
    $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'max(date_operation) as dernier_vol')
            ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
            ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
            ->where('duree_minute is not null');
    if ($select->query()->rowCount() == 1) {
        $dt = new DateTime($select->query()->fetch()->dernier_vol);
        $dernier_vol = $dt->format('j M Y');
    }
} catch (Exception $e) {
    Analog\Analog::log(
            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
            $e->getTraceAsString(), Analog\Analog::ERROR
    );
}

$complement = new PiloteAdherentComplement($pseudo);

$tpl->assign('page_title', _T("SITUATION AERO.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('liste_adherents', $liste_adherents);
$tpl->assign('adherent_selectionne', $pseudo);
$tpl->assign('solde_debiteur', '');
$tpl->assign('solde', $solde);
$tpl->assign('solde_format', number_format($solde, 2, ',', ' ') . ' EUR');

$duree_heure = floor($duree_vol / 60);
$duree_minute = $duree_vol - $duree_heure * 60;
if ($duree_heure == 0) {
    $tpl->assign('temps_vol', $duree_minute . ' min');
} else {
    $tpl->assign('temps_vol', $duree_heure . ' h ' . $duree_minute . ' min');
}

$duree_heure = floor($duree_vol_glissant / 60);
$duree_minute = $duree_vol_glissant - $duree_heure * 60;
if ($duree_heure == 0) {
    $tpl->assign('temps_vol_glissant', $duree_minute . ' min');
} else {
    $tpl->assign('temps_vol_glissant', $duree_heure . ' h ' . $duree_minute . ' min');
}

$tpl->assign('date_license', $complement->date_fin_license);
$tpl->assign('visite_medicale', $complement->date_visite_medicale);
$tpl->assign('vol_controle', $complement->date_vol_controle);
$tpl->assign('nb_atterissages', $nb_atterr);
$tpl->assign('dernier_vol', $dernier_vol);

$content = $tpl->fetch('situation_aero.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
