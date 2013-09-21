<?php

/**
 * Public Class PiloteAvion
 * Store informations about a plane
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
 * Class to store a plane into databse
 *
 * @category  Classes
 * @name      PiloteAvion
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteAvion {

    const TABLE = 'avions';
    const PK = 'avion_id';

    private $_fields = array(
        '_avion_id' => 'integer',
        '_nom' => 'string',
        '_nom_court' => 'string',
        '_marque_type' => 'string',
        '_type_aeronef' => 'string',
        '_immatriculation' => 'string',
        '_couleur' => 'string',
        '_nb_places' => 'integer',
        '_cout_horaire' => 'double',
        '_remarques' => 'string',
        '_DC_autorisee' => 'bool',
        '_est_remorqueur' => 'bool',
        '_est_reservable' => 'bool',
        '_actif' => 'bool',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime',
    );
    private $_avion_id;
    private $_nom;
    private $_nom_court;
    private $_marque_type;
    private $_type_aeronef;
    private $_immatriculation;
    private $_couleur;
    private $_nb_places;
    private $_cout_horaire;
    private $_remarques = '';
    private $_DC_autorisee;
    private $_est_remorqueur;
    private $_est_reservable;
    private $_actif = true;
    private $_date_creation;
    private $_date_modification;
    private $_numero_ligne;
    private $_picture;                  // photo de l'avion
    private $_date_debut;               // date début dispo
    private $_date_fin;                 // date fin dispo
    private $_tooltip;

    /**
     * Construit un nouvel avion vierge ou en charge un depuis la base de données
     * à partir de son ID (int) ou son immatriculation (string)
     * 
     * @param int|string|object $args Peut être null, un ID, une immatriculation ou une ligne de BDD
     */

    public function __construct($args = null) {
        global $zdb;

        $this->_picture = new PiloteAvionPicture();

        if (is_string($args)) {
            try {
                $select = new Zend_Db_Select($zdb->db);
                $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                        ->where('immatriculation = ?', $args);
                if ($select->query()->rowCount() == 1) {
                    $this->_loadFromRS($select->query()->fetch());
                }
            } catch (Exception $e) {
                Analog\Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog\Analog::ERROR
                );
            }
        } else if (is_int($args)) {
            try {
                $select = new Zend_Db_Select($zdb->db);
                $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                        ->where(self::PK . ' = ' . $args);
                if ($select->query()->rowCount() == 1) {
                    $this->_loadFromRS($select->query()->fetch());
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
        $this->_avion_id = $r->avion_id;
        $this->_nom = $r->nom;
        $this->_nom_court = $r->nom_court;
        $this->_marque_type = $r->marque_type;
        $this->_type_aeronef = $r->type_aeronef;
        $this->_immatriculation = $r->immatriculation;
        $this->_couleur = $r->couleur;
        $this->_nb_places = $r->nb_places;
        $this->_cout_horaire = $r->cout_horaire;
        $this->_remarques = $r->remarques;
        $this->_DC_autorisee = $r->DC_autorisee == '1' ? true : false;
        $this->_est_remorqueur = $r->est_remorqueur == '1' ? true : false;
        $this->_est_reservable = $r->est_reservable == '1' ? true : false;
        $this->_actif = $r->actif == '1' ? true : false;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
        $this->_picture = new PiloteAvionPicture((int) $this->_avion_id);
        //$this->_date_debut = $r->date_debut;
        //$this->_date_fin = $r->date_fin;
    }

    /**
     * Does the current plane has a picture?
     *
     * @return boolean
     */
    public function hasPicture() {
        return $this->_picture->hasPicture();
    }

    /**
     * Enregistre l'élément en cours que ce soit en insert ou update
     * 
     * @global type $hist Accesseur à l'ajout d'information d'historique
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

            if (!isset($this->_avion_id) || $this->_avion_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $add = $zdb->db->insert(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values);
                if ($add > 0) {
                    $this->_avion_id = $zdb->db->lastInsertId();
                } else {
                    throw new Exception(_T("AVION.AJOUT ECHEC"));
                }
            } else {
                $edit = $zdb->db->update(
                        PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values, self::PK . '=' . $this->_avion_id
                );
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
     * Renvoie le nombre d'avions actifs
     * 
     * @return int Le nombre d'avions actifs 
     */
    public static function getNombreAvionsActifs() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('actif = 1');
            return $select->query()->rowCount();
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    public static function getImmatriculation($avion_id) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('immatriculation'))
                    ->where('avion_id = ?', $avion_id);
            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() == 1) {
                $immat = $select->query()->fetch();
                return $immat->immatriculation;
            }
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi une liste d'avions actifs triés par par le tri
     * 
     * @param string $tri Colonne de tri
     * @param string $direction asc ou desc
     * @param int $no_page N° de la page à récupérer (commence avec 1)
     * @param int $lignes_par_page Nombre de lignes par page
     * 
     * @return PiloteAvion[] La liste des avions actifs triés par le tri + direction selon le n° de page et le nombre de lignes par pages 
     */
    public static function getTousAvionsActifs($tri, $direction, $no_page, $lignes_par_page) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('actif = 1')
                    ->joinLeft(PREFIX_DB . PILOTE_PREFIX . PiloteAvionDispo::TABLE, PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.' . self::PK . ' = ' . PREFIX_DB . PILOTE_PREFIX . PiloteAvionDispo::TABLE . '.avion_id', array('date_debut', 'date_fin'))
                    ->order(array($tri . ' ' . $direction, 'date_debut asc'))
                    ->limitPage($no_page, $lignes_par_page);

            $avions = array();
            $result = $select->query()->fetchAll();
            for ($i = 0; $i < count($result); $i++) {
                $avion = new PiloteAvion($result[$i]);
                $avion->numero_ligne = $i;
                $avions[$avion->avion_id] = $avion;
            }
            return $avions;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi tous les avions réservables pour la date donnée. Une entrée dans la table avions_dispo doit être présente
     * 
     * @return PiloteAvion 
     */
    public static function getTousAvionsReservables() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('actif = 1')
                    ->where('est_reservable = 1')
                    ->order('nom');

            $avions = array();
            $results = $select->query()->fetchAll();
            foreach ($results as $row) {
                $avion = new PiloteAvion($row);
                $avions[$avion->avion_id] = $avion;
            }
            return $avions;
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
            case 'cout_horaire':
                return number_format($this->_cout_horaire, 2, ',', ' ') . ' EUR';
            case 'date_debut':
                if (!is_null($this->_date_debut)) {
                    return date('d/m/Y', strtotime($this->_date_debut));
                }
                return 'n/a';
            case 'date_fin':
                if (!is_null($this->_date_fin)) {
                    return date('d/m/Y', strtotime($this->_date_fin));
                }
                return 'n/a';
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
