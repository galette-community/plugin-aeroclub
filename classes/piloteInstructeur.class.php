<?php

/**
 * Public Class PiloteInstructeur
 * Store information about a flight teacher
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
 * Class to store instructors
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
class PiloteInstructeur {

    const TABLE = 'instructeurs';
    const PK = 'instructeur_id';

    private $_fields = array(
        '_instructeur_id' => 'integer',
        '_code' => 'string',
        '_nom' => 'string',
        '_code_adherent' => 'string',
        '_adherent_id' => 'integer',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime',
    );
    private $_instructeur_id;
    private $_code;
    private $_nom;
    private $_code_adherent;
    private $_adherent_id;
    private $_date_creation;
    private $_date_modification;

    /**
     * Construit un nouvel instructeur soit vierge, soit à partir de son ID, son code ou d'une ligne de BDD
     * 
     * @param string|int|object $args Peut être un ID, un code ou une ligne de BDD
     */
    public function __construct($args = null) {
        global $zdb;

        if (is_string($args)) {
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                        ->where(array('code' => $args));
                $result = $zdb->execute($select);
                if ($result->count() == 1) {
                    $this->_loadFromRS($result->current());
                }
            } catch (Exception $e) {
                Analog\Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog\Analog::ERROR
                );
            }
        } else if (is_int($args)) {
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                        ->where(array(self::PK => $args));
                $result = $zdb->execute($select);
                if ($result->count() == 1) {
                    $this->_loadFromRS($result->current());
                }
            } catch (Exception $e) {
                Analog\Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog\Analog::ERROR
                );
            }
        } else if (is_object($args)) {
            $this->_loadFromRS($args);
        }
    }

    /**
     * Populate object from a resultset row
     *
     * @param ResultSet $r the resultset row
     *
     * @return void
     */
    private function _loadFromRS($r) {
        $this->_instructeur_id = $r->instructeur_id;
        $this->_code = $r->code;
        $this->_nom = $r->nom;
        $this->_code_adherent = $r->code_adherent;
        $this->_adherent_id = $r->adherent_id;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
    }

    /**
     * Renvoie le nombre d'instructeurs enregistrés en base
     * 
     * @return int le nombre d'instructeurs présents dans la base 
     */
    public static function getNombreInstructeurs() {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('nb' => new Zend\Db\Sql\Predicate\Expression('count(*)')));

            $result = $zdb->execute($select);
            return $result->current()->nb;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoie les instructeurs connus dans la base, trié selon l'ordre indiqué, le n° de page et le nombre de lignes par pages
     * 
     * @param string $tri Colonne de tri
     * @param string $direction asc ou desc
     * @param int $no_page N° de la page à afficher (commence à 1)
     * @param int $lignes_par_page Nombre de lignes par page
     * 
     * @return PiloteInstructeur La liste des instructeurs selon les critères donnés
     */
    public static function getTousInstructeurs($tri, $direction, $no_page, $lignes_par_page) {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->order($tri . ' ' . $direction)
                    ->limit($lignes_par_page)
                    ->offset($no_page * $lignes_par_page);

            $instructeurs = array();
            $results = $zdb->execute($select);
            foreach ($results as $row) {
                $instructeurs[] = new PiloteInstructeur($row);
            }
            return $instructeurs;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Indique si un pilote est enregistré dans le système en tant qu'instructeur
     * 
     * @param string $login Identifiant du pilote dont on cherche à savoir si il est instructeur
     * @return boolean True si identifiant est instructeur, False sinon
     */
    public static function isPiloteInstructeur($login) {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->where(array('code_adherent' => $login));

            $results = $zdb->execute($select);
            return $results->count() == 1;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Enregistre l'élément en cours que ce soit en insert ou update
     * 
     * @return bool False si l'enregistrement a échoué, true si aucune erreur
     */
    public function store() {
        global $zdb;

        try {
            $values = array();

            foreach ($this->_fields as $k => $v) {
                $values[substr($k, 1)] = $this->$k;
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_instructeur_id) || $this->_instructeur_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $insert = $zdb->insert(PILOTE_PREFIX . self::TABLE)
                        ->values($values);
                $add = $zdb->execute($insert);
                if ($add > 0) {
                    $this->_instructeur_id = $zdb->driver->getLastGeneratedValue();
                } else {
                    throw new Exception(_T("AVION.AJOUT ECHEC"));
                }
            } else {
                $update = $zdb->update(PILOTE_PREFIX . self::TABLE)
                        ->set($values)
                        ->where(array(self::PK => $this->_instructeur_id));
                $zdb->execute($update);
            }
            return true;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Global getter method
     *
     * @param string $name name of the property we want to retrive
     *
     * @return false|object the called property
     */
    public function __get($name) {
        $rname = '_' . $name;
        if (substr($rname, 0, 3) == '___') {
            return null;
        }
        switch ($name) {
            default:
                return $this->$rname;
        }
    }

    /**
     * Global setter method
     *
     * @param string $name  name of the property we want to assign a value to
     * @param object $value a relevant value for the property
     *
     * @return void
     */
    public function __set($name, $value) {
        $rname = '_' . $name;
        $this->$rname = $value;
    }
}
