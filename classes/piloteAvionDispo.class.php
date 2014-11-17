<?php

/**
 * Public Class PiloteAvionDispo
 * Store informations about disponibilities of a plane
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
 * Class to store the disponibilities of a plane
 *
 * @category  Classes
 * @name      PiloteAvionDispo
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteAvionDispo {

    const TABLE = 'avions_dispo';
    const PK = 'dispo_id';

    private $_fields = array(
        '_dispo_id' => 'integer',
        '_avion_id' => 'integer',
        '_date_debut' => 'date',
        '_date_fin' => 'date',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime',
    );
    private $_dispo_id;
    private $_avion_id;
    private $_date_debut;
    private $_date_fin;
    private $_date_creation;
    private $_date_modification;

    /**
     * Construit une nouvelle disponibilité vierge où en charge une dans la base à partir
     * de son ID (int)
     * 
     * @param int|object $args ID ou ligne de BDD
     */
    public function __construct($args = null) {
        global $zdb;

        if (is_int($args)) {
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
        $this->_dispo_id = $r->dispo_id;
        $this->_avion_id = $r->avion_id;
        $this->_date_debut = $r->date_debut;
        $this->_date_fin = $r->date_fin;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
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

            if (!$this->_date_fin || $this->_date_fin == '') {
                $values['date_fin'] = new Zend\Db\Sql\Predicate\Expression('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_dispo_id) || $this->_dispo_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $insert = $zdb->insert(PILOTE_PREFIX . self::TABLE)
                        ->values($values);
                $add = $zdb->execute($insert);
                if ($add > 0) {
                    $this->_dispo_id = $zdb->db->lastInsertId();
                } else {
                    throw new Exception(_T("AVION.AJOUT ECHEC"));
                }
            } else {
                $update = $zdb->update(PILOTE_PREFIX . self::TABLE)
                        ->set($values)
                        ->where(array(self::PK => $this->_dispo_id));
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
     * Renvoie toutes les disponibilités pour un avion
     * 
     * @param int $avion_id Avion recherché
     * 
     * @return array Liste de disponibilités pour un avion trié par date début
     */
    public static function getDisponibilitesPourAvion($avion_id) {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->where(array('avion_id' => $avion_id))
                    ->order('date_debut asc');
            $dispo = array();
            $results = $zdb->execute($select);
            foreach ($results as $row) {
                $dispo[] = new PiloteAvionDispo($row);
            }
            return $dispo;
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
            case 'date_debut':
                return date('d/m/Y', strtotime($this->_date_debut));
            case 'date_fin':
                if (!is_null($this->_date_fin)) {
                    return date('d/m/Y', strtotime($this->_date_fin));
                }
                return '';
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

?>
