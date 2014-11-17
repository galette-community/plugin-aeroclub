<?php

/**
 * Public Class PiloteSQLScripts
 * Store information to show which SQL script has been already played
 * on the database.
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

/**
 * Class to execute a SQL script
 *
 * @category  Classes
 * @name      PiloteInstructeur
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteSQLScripts {

    const TABLE = 'sql_scripts';

    /**
     * Lance l'execution d'un script SQL en traduisant les noms de tables pour respecter les préfixes
     * et noms internes de l'application.
     * 
     * @param string $nom_fichier Nom du fichier SQL à exécuter
     * 
     * @return bool Renvoie true si l'exécution est réussie
     */
    public static function executeSQLScript($nom_fichier) {
        global $zdb;

        try {
            $schema = file_get_contents($nom_fichier);
        } catch (Exception $e) {
            Analog\Analog::log("Erreur lors de l'ouverture du fichier | " . $e->getMessage(), Analog\Analog::ERROR);
        }

        $schema = str_replace(array('{PREFIX_DB}',
            '{PILOTE_PREFIX}',
            '{PiloteSQLScripts::TABLE}',
            '{PiloteOperation::TABLE}',
            '{Adherent::TABLE}',
            '{PiloteParametre::TABLE}',
            '{PiloteAdherentComplement::TABLE}',
            '{PiloteAvion::TABLE}',
            '{PiloteReservation::TABLE}',
            '{PiloteInstructeur::TABLE}',
            '{PiloteAvionDispo::TABLE}'), array(PREFIX_DB,
            PILOTE_PREFIX,
            self::TABLE,
            PiloteOperation::TABLE,
            Galette\Entity\Adherent::TABLE,
            PiloteParametre::TABLE,
            PiloteAdherentComplement::TABLE,
            PiloteAvion::TABLE,
            PiloteReservation::TABLE,
            PiloteInstructeur::TABLE,
            PiloteAvionDispo::TABLE), $schema);

        $schema = explode(";\n", $schema);
        $schema = array_map('trim', $schema);
        $schema = array_filter($schema, 'strlen');

        $erreur = false;
        $liste_erreurs = array();

        foreach ($schema as $sql) {
            try {
                $zdb->db->query($sql);
            } catch (Exception $e) {
                $erreur = true;
                $liste_erreurs[] = $e->getMessage();
                Analog\Analog::log("Erreur SQL | " . $e->getMessage(), Analog\Analog::ERROR);
            }
        }

        if ($erreur) {
            return $liste_erreurs;
        }
        return true;
    }

    /**
     * Ajoute une ligne dans la table d'exécution des scripts SQL pour indiquer lequel a été
     * exécuté à quelle date.
     * 
     * @param string $nom_script Nom du script à ajouter à la table
     */
    public static function ajouterLogSQLScript($nom_script) {
        global $zdb;

        $values = array(
            'sql_script' => $nom_script,
            'date_execution' => date('Y-m-d H:i:s')
        );

        try {
            $zdb->db->insert(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values);
        } catch (Exception $e) {
            Analog\Analog::log("Erreur SQL | " . $e->getMessage(), Analog\Analog::ERROR);
        }
    }

    /**
     * Cherche si un script a été exécuté, si oui, combien de fois et date de dernière exécution.
     * 
     * @param string $nom_script Nom du script recherché
     * 
     * @return $o->nb_execution + $o->derniere_execution pour le nombre d'exécution et la date de dernière exécution
     */
    public static function chercheNbExecutionDateExecution($nom_script) {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('nb_execution' => new Zend\Db\Sql\Predicate\Expression('count(*)'),
                        'derniere_execution' => new Zend\Db\Sql\Predicate\Expression('max(date_execution)')))
                    ->where(array('sql_script' => $nom_script));
            return $zdb->execute($select);
        } catch (Exception $e) {
            Analog\Analog::log("Erreur SQL | " . $e->getMessage(), Analog\Analog::ERROR);
        }
    }
}
