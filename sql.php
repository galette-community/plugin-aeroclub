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
if (filter_has_var(INPUT_POST, 'nom_script') && strlen(filter_input(INPUT_POST, 'nom_script')) > 0) {
    $script = filter_input(INPUT_POST, 'nom_script');
    $resultat = PiloteSQLScripts::executeSQLScript('./sql/' . $script);
    PiloteSQLScripts::ajouterLogSQLScript($script);

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
    $liste_tables[$table]->nb_lignes = PiloteParametre::nbEnregistrementTable(PILOTE_PREFIX . $table);
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
if (filter_has_var(INPUT_GET, 'table') && stripos(filter_input(INPUT_GET, 'table'), PILOTE_PREFIX) !== false) {
    try {
        $metadata = new \Zend\Db\Metadata\Metadata($zdb->db);
        $table = $metadata->getTable(filter_input(INPUT_GET, 'table'));
        $infos = $table->getColumns();
        $i = 0;

        foreach ($infos as $col) {
            $info_colonne = new stdClass();
            $info_colonne->numero = ++$i;
            $info_colonne->table_name = $col->getTableName();
            $info_colonne->name = $col->getName();
            $info_colonne->data_type = $col->getDataType();
            $info_colonne->length = $col->getCharacterMaximumLength();
            $info_colonne->unsigned = $col->getNumericUnsigned();
            $info_colonne->nullable = $col->getIsNullable();
            //$info_colonne->primary = $colonne['PRIMARY'];
            //$info_colonne->primary_position = $colonne['PRIMARY_POSITION'];
            //$info_colonne->identity = $colonne['IDENTITY'];

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
$dh = opendir('./scripts');
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
    $script->modifie = date('d/m/Y H:i', filemtime('./scripts/' . $fichier));
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
