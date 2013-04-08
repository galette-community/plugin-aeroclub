<?php

/**
 * Public Class PiloteAdherentComplement
 * Store more basic information about a member
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
 * Complementary informations for a pilot, that do not fit in adherent table
 *
 * @category  Classes
 * @name      PiloteAdherentComplement
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
*/
class PiloteAdherentComplement {
    const TABLE = "adherent_complement";
    const PK = "complement_id";

    private $_fields = array(
        '_complement_id' => 'integer',
        '_id_adherent' => 'integer',
        '_tel_travail' => 'varchar',
        '_no_fax' => 'varchar',
        '_actif' => 'bool',
        '_est_eleve' => 'bool',
        '_indicateur_journalier' => 'bool',
        '_date_dernier_vol' => 'date',
        '_date_visite_medicale' => 'date',
        '_date_fin_license' => 'date',
        '_date_vol_controle' => 'date',
        '_indicateur_forfait' => 'bool',
        '_ck_situation_aero' => 'bool',
        '_no_bb' => 'integer',
        '_no_ppl' => 'integer',
        '_releve_mail' => 'bool',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime'
    );

    /**
     * Construit un nouveau complément d'adhérent vide ou à partir du login de l'adhérent
     * 
     * @param type $args Si c'est une string, cela doit correspondre au login de l'adhérent
     */
    public function __construct($args = null) {
        global $zdb;

        if (is_object($args)) {
            $this->_loadFromRS($args);
        } else if (is_string($args)) {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $args);
            if ($select->query()->rowCount() == 1) {
                $this->_loadFromRS($select->query()->fetch());
            }
        }
    }

    private $_complement_id;
    private $_id_adherent;
    private $_tel_travail;
    private $_no_fax;
    private $_actif;
    private $_est_eleve;
    private $_indicateur_journalier;
    private $_date_dernier_vol;
    private $_date_visite_medicale;
    private $_date_fin_license;
    private $_date_vol_controle;
    private $_indicateur_forfait;
    private $_ck_situation_aero;
    private $_no_bb;
    private $_no_ppl;
    private $_releve_mail;
    private $_date_creation;
    private $_date_modification;

    /**
     * Populate object from a resultset row
     *
     * @param ResultSet $r the resultset row
     *
     * @return void
     */
    private function _loadFromRS($r) {
        $this->_complement_id = $r->complement_id;
        $this->_id_adherent = $r->id_adherent;
        $this->_complement_id = $r->complement_id;
        $this->_tel_travail = $r->tel_travail;
        $this->_no_fax = $r->no_fax;
        $this->_actif = $r->actif;
        $this->_est_eleve = $r->est_eleve;
        $this->_indicateur_journalier = $r->indicateur_journalier;
        $this->_date_dernier_vol = $r->date_dernier_vol;
        $this->_date_visite_medicale = $r->date_visite_medicale;
        $this->_date_fin_license = $r->date_fin_license;
        $this->_date_vol_controle = $r->date_vol_controle;
        $this->_indicateur_forfait = $r->indicateur_forfait;
        $this->_ck_situation_aero = $r->ck_situation_aero;
        $this->_no_bb = $r->no_bb;
        $this->_no_ppl = $r->no_ppl;
        $this->_releve_mail = $r->releve_mail;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
    }

    /**
     * Cherche le complément d'adhérent par son ID
     * 
     * @param int $id Identifiant du pilote recherché
     * 
     * @return Un complément d'adhérient vide si non existant ou le complément avec ses données
     */
    public static function findByLogin($id) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('id_adherent = ?', $id);
            if ($select->query()->rowCount() == 1) {
                return new PiloteAdherentComplement($select->query()->fetch());
            } else {
                return new PiloteAdherentComplement();
            }
        } catch (Exception $e) {
            Analog\Analog::log("Erreur" . $e->getMessage(), Analog\Analog::ERROR);
            return false;
        }
    }

    /**
     * Retrieve fields from database
     *
     * @return array
     */
    public static function getDbFields() {
        global $zdb;
        return array_keys($zdb->db->describeTable(PREFIX_DB . PILOTE_PREFIX . self::TABLE));
    }

    /**
     * Enregistre l'élément en cours que ce soit en insert ou update
     * 
     * @return bool False si l'enregistrement a échoué, true si aucune erreur
     */
    public function store() {
        global $zdb, $hist;

        try {
            $values = array();

            foreach ($this->_fields as $k => $v) {
                $values[substr($k, 1)] = $this->$k;
            }

            //an empty value will cause date to be set to 1901-01-01, a null
            //will result in 0000-00-00. We want a database NULL value here.
            if (!$this->_date_dernier_vol || $this->_date_dernier_vol == '') {
                $values['date_dernier_vol'] = new Zend_Db_Expr('NULL');
            }
            if (!$this->_date_fin_license || $this->_date_fin_license == '') {
                $values['date_fin_license'] = new Zend_Db_Expr('NULL');
            }
            if (!$this->_date_visite_medicale || $this->_date_visite_medicale == '') {
                $values['date_visite_medicale'] = new Zend_Db_Expr('NULL');
            }
            if ($this->_date_vol_controle || $this->_date_vol_controle == '') {
                $values['date_vol_controle'] = new Zend_Db_Expr('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_complement_id) || $this->_complement_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $add = $zdb->db->insert(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values);
                if ($add > 0) {
                    $this->_complement_id = $zdb->db->lastInsertId();
                    // logging
                    $hist->add(_T("COMPLEMENT.AJOUT SUCCES"), strtoupper($this->_login_adh));
                } else {
                    $hist->add(_T("COMPLEMENT.AJOUT ECHEC"));
                    throw new Exception(_T("COMPLEMENT.AJOUT ECHEC"));
                }
            } else {
                $edit = $zdb->db->update(
                        PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values, self::PK . '=' . $this->_complement_id
                );
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
                if ($edit > 0) {
                    $hist->add(
                            _T("COMPLEMENT.MISE A JOUR"), strtoupper($this->_login_adh)
                    );
                }
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
     * Renvoi un booléen indiquant si la date de license ou médicale du pilote est dépassée.
     * 
     * @return bool 
     */
    public function isDateLicenseOuMedicaleDepassee(){
        return $this->_date_fin_license < date('Y-m-d') || $this->_date_visite_medicale < date('Y-m-d');
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
            case 'date_dernier_vol':
            case 'date_fin_license':
            case 'date_visite_medicale':
            case 'date_vol_controle':
                if ($this->$rname != '') {
                    try {
                        $d = new DateTime($this->$rname);
                        return $d->format('j M Y');
                    } catch (Exception $e) {
                        //oops, we've got a bad date :/
                        Analog\Analog::log(
                                'Bad date (' . $this->$rname . ') | ' .
                                $e->getMessage(), Analog\Analog::INFO
                        );
                        return $this->$rname;
                    }
                } else {
                    return '';
                }
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
