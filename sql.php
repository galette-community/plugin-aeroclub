<?php

/**
 * Display informations about SQL tables of the Pilote Plugin for the admin.
 * Allows the Admin to execute SQL scripts stored in plugin/sql directory.
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

$erreur = false;
$liste_erreurs = array();

/**
 * Execution du script demandé si nécessaire
 */
if (array_key_exists('nom_script', $_POST) && $_POST['nom_script'] != '') {
    $resultat = PiloteSQLScripts::executeSQLScript('./sql/' . $_POST['nom_script']);
    PiloteSQLScripts::ajouterLogSQLScript($_POST['nom_script']);

    if (is_array($resultat)) {
        $erreur = true;
        $liste_erreurs = $resultat;
    }
}

/**
 * Test si les tables nécessaire existe + affichage nombre lignes dedans
 */
$array_tables = array(
    PiloteAdherentComplement::TABLE,
    PiloteOperation::TABLE,
    PiloteParametre::TABLE,
    PiloteAvion::TABLE,
    PiloteAvionDispo::TABLE,
    PiloteAvionPicture::TABLE,
    PiloteReservation::TABLE,
    PiloteInstructeur::TABLE,
    PiloteSQLScripts::TABLE,    
);

$liste_tables = array();
foreach ($array_tables as $table) {
    $liste_tables[$table] = new stdClass();
    $liste_tables[$table]->nom_table = PREFIX_DB . PILOTE_PREFIX . $table;
    $liste_tables[$table]->existe = PiloteParametre::tableExiste(PREFIX_DB . PILOTE_PREFIX . $table);
    $liste_tables[$table]->nb_lignes = PiloteParametre::nbEnregistrementTable(PREFIX_DB . PILOTE_PREFIX . $table);
    $liste_tables[$table]->version = '';
    if ($liste_tables[$table]->existe) {
        $liste_tables[$table]->version = PiloteParametre::versionTable(PREFIX_DB . PILOTE_PREFIX . $table);
    }
}


$montre_infos_table = false;
$infos_table = array();

/**
 * Si on demande à voir la structure de la table et qu'il s'agit d'une table du plugin
 * on en cherche les infos
 */
if (array_key_exists('table', $_GET) && stripos($_GET['table'], PILOTE_PREFIX) !== false) {
    try {
        $infos = $zdb->db->describeTable($_GET['table']);

        $mysql_infos = array();
        try {
            $query = $zdb->db->query('SHOW COLUMNS FROM ' . $_GET['table']);
            $mysql_infos = $query->fetchAll();
        } catch (Exception $e) {
            Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
        }

        foreach ($infos as $colonne) {
            $info_colonne = new stdClass();
            $info_colonne->numero = $colonne['COLUMN_POSITION'];
            $info_colonne->table_name = $colonne['TABLE_NAME'];
            $info_colonne->name = $colonne['COLUMN_NAME'];
            $info_colonne->data_type = $colonne['DATA_TYPE'];
            $info_colonne->length = $colonne['LENGTH'];
            $info_colonne->unsigned = $colonne['UNSIGNED'];
            $info_colonne->nullable = $colonne['NULLABLE'];
            $info_colonne->primary = $colonne['PRIMARY'];
            $info_colonne->primary_position = $colonne['PRIMARY_POSITION'];
            $info_colonne->identity = $colonne['IDENTITY'];
            foreach ($mysql_infos as $my_info) {
                if (is_object($my_info) && $my_info->Field == $info_colonne->name && $my_info->Key != '') {
                    $info_colonne->index = 1;
                }
            }

            $infos_table[] = $info_colonne;
        }
        $montre_infos_table = true;
    } catch (Exception $e) {
        Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
    }
}

/**
 * Récupération de la liste des scripts présents dans le répertoire SQL
 */
$liste_scripts = array();
$fichiers_sql = array();
$dh = opendir('./sql');
while (false !== ($filename = readdir($dh))) {
    if (preg_match('/.sql$/', $filename)) {
        $fichiers_sql[] = $filename;
    }
}

/**
 * Tri par ordre alphabétique des scripts trouvés
 */
sort($fichiers_sql);
$row = 0;

/**
 * Ajout d'informations aux scripts (date modif, nb exécution, dernière exécution)
 */
foreach ($fichiers_sql as $fichier) {
    $script = new stdClass();
    $script->filename = $fichier;
    $script->numero_ligne = $row++;
    $script->modifie = date('d/m/Y H:i', filemtime('./sql/' . $fichier));
    $result = PiloteSQLScripts::chercheNbExecutionDateExecution($fichier);
    $script->nb_execution = $result->nb_execution;
    $script->derniere_execution = '';
    if ($result->derniere_execution != null) {
        $dt = new DateTime($result->derniere_execution);
        $script->derniere_execution = $dt->format('d M Y à H:i');
    }
    $liste_scripts[] = $script;
}


$tpl->assign('page_title', _T("SQL.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('erreur', $erreur);
$tpl->assign('liste_erreurs', $liste_erreurs);

$tpl->assign('liste_tables', $liste_tables);

$tpl->assign('liste_scripts', $liste_scripts);
$tpl->assign('montre_infos_table', $montre_infos_table);
if ($montre_infos_table) {
    $tpl->assign('nom_table', $_GET['table']);
}
$tpl->assign('infos_table', $infos_table);

$content = $tpl->fetch('sql.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
