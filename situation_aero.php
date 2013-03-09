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

$liste_adherents = PiloteOperation::getAdherentsActifs();

/**
 * Calcul du solde du pilote
 */
$solde = PiloteOperation::getSoldeCompteForLogin($pseudo, date('Y'));

/**
 * Calcul du temps de vol du pilote
 */
$duree_vol = PiloteOperation::getTempsVolForLogin($pseudo, date('Y'));

/**
 * Calcul du temps de vol du pilote sur 1 année glissante
 */
$duree_vol_glissant = PiloteOperation::getTempsVolAnneeGlissanteForLogin($pseudo);

/**
 * Calcul du nombre d'atterrissages dans les 3 derniers mois
 */
$nb_atterr = PiloteOperation::getNombreAtterrissageTroisDerniersMois($pseudo);

/**
 * Récupération du dernier vol du pilote
 */
$dernier_vol = PiloteOperation::getDernierVolForLogin($pseudo);

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
