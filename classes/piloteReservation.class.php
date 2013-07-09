<?php

/**
 * Public Class PiloteReservation
 * Store the informations needed for a reservation of a plane
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
 * Class to store all informations of planes reservations
 *
 * @category  Classes
 * @name      PiloteReservation
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteReservation {

    const TABLE = 'reservations';
    const PK = 'reservation_id';

    private $_fields = array(
        '_reservation_id' => 'int',
        '_id_adherent' => 'int',
        '_id_avion' => 'int',
        '_id_instructeur' => 'int',
        '_heure_debut' => 'datetime',
        '_heure_fin' => 'datetime',
        '_nom' => 'string',
        '_destination' => 'string',
        '_email_contact' => 'string',
        '_no_portable' => 'string',
        '_commentaires' => 'string',
        '_est_reservation_club' => 'bool',
        '_est_rapproche' => 'bool',
        '_id_operation' => 'int',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime',
    );
    private $_reservation_id;
    private $_id_adherent;
    private $_id_avion;
    private $_id_instructeur;
    // Utilisé sur rapprochement pour formatage
    private $_instructeur;
    private $_heure_debut;
    // Utilisé sur rapprochement pour formatage
    private $_heure_debut_IHM;
    private $_heure_fin;
    // Utilisé sur rapprochement pour durée minutes
    private $_minute;
    // Utilisé sur rapprochement pour durée heures
    private $_heure;
    // Utilisé sur rapprochement pour durée heures
    private $_libelle;
    private $_nom;
    private $_destination;
    private $_email_contact;
    private $_no_portable;
    private $_commentaires;
    private $_est_reservation_club = false;
    private $_est_rapproche = false;
    private $_id_operation;
    private $_date_creation;
    private $_date_modification;

    /**
     * Créé une nouvelle reservation vierge ou charge une réservation à partir de son ID
     * 
     * @param int|object $args ID ou ligne de BDD
     * @param bool $cloned Indique si la réservation est clonée d'une autre (si true, l'ID réservation est mis à null)
     */
    public function __construct($args = null, $cloned = false) {
        global $zdb;

        if (is_int($args)) {
            try {
                $select = new Zend_Db_Select($zdb->db);
                $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                        ->where(self::PK . ' = ' . $args);
                if ($select->query()->rowCount() == 1) {
                    $this->_loadFromRS($select->query()->fetch());
                }
                if ($cloned) {
                    unset($this->_reservation_id);
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
        $this->_reservation_id = $r->reservation_id;
        $this->_id_adherent = $r->id_adherent;
        $this->_id_avion = $r->id_avion;
        $this->_id_instructeur = $r->id_instructeur;
        $this->_heure_debut = $r->heure_debut;
        $this->_heure_fin = $r->heure_fin;
        $this->_nom = $r->nom;
        $this->_destination = $r->destination;
        $this->_email_contact = $r->email_contact;
        $this->_no_portable = $r->no_portable;
        $this->_commentaires = $r->commentaires;
        $this->_est_reservation_club = $r->est_reservation_club == '1' ? true : false;
        $this->_est_rapproche = $r->est_rapproche == '1' ? true : false;
        $this->_id_operation = $r->id_operation;
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

            if ($values['id_instructeur'] == 'null') {
                $values['id_instructeur'] = new Zend_Db_Expr('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_reservation_id) || $this->_reservation_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $add = $zdb->db->insert(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values);
                if ($add > 0) {
                    $this->_reservation_id = $zdb->db->lastInsertId();
                } else {
                    throw new Exception(_T("RESERVATION.AJOUT ECHEC"));
                }
            } else {
                $edit = $zdb->db->update(
                        PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values, self::PK . '=' . $this->_reservation_id
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
     * Renvoie toutes les réservations d'un avion pour la période comprise entre le jour de début
     * et le jour de fin (donner les jours au format SQL : AAAA-MM-JJ)
     * 
     * @param int $avion_id Id de l'avion dont on veut les réservations
     * @param string $jour_debut 1er jour de réservation (format AAAA-MM-JJ)
     * @param string $jour_fin Dernier jour de réservation (format AAAA-MM-JJ)
     * 
     * @return PiloteReservation Une liste de réservations
     */
    public static function getReservationsPourAvion($avion_id, $jour_debut, $jour_fin) {
        global $zdb;
        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('id_avion = ' . $avion_id)
                    ->where('heure_debut >= ?', $jour_debut . ' 00:00:00')
                    ->where('heure_debut <= ?', $jour_fin . ' 23:59:59');
            $reservations = array();
            $rows = $select->query()->fetchAll();
            foreach ($rows as $row) {
                $resa = new PiloteReservation($row);
                $reservations[] = $resa;
            }
            return $reservations;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Supprime une réservation
     * 
     * @param int $reservation_id L'ID de la réservation à supprimer
     * 
     * @return bool True si supprimé, False si échec
     */
    public static function supprimeReservation($reservation_id) {
        global $zdb;

        try {
            $zdb->db->delete(PREFIX_DB . PILOTE_PREFIX . self::TABLE, self::PK . ' = ' . $reservation_id);
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
     * Cherche toutes les réservations passées et qui n'ont pas encore été rapprochées
     * 
     * @return PiloteReservation Une liste de réservations correspondant aux critères
     */
    public static function getReservationsNonRapprochees() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->where('est_rapproche = 0')
                    ->where('heure_fin < ?', date('Y-m-d H:i:s'))
                    ->order('heure_debut');
            $reservations = array();
            $rows = $select->query()->fetchAll();
            foreach ($rows as $row) {
                $resa = new PiloteReservation($row);
                $reservations[$resa->reservation_id] = $resa;
            }
            return $reservations;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /*     * ************************************************************************** *
     *                                                                             *
     *                       __        ____                                        *
     *                 ___  / /  ___  / __/__  __ _____________ ___                *
     *                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<                *
     *               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/                *
     *              /_/       /_/                                                  *
     *                                                                             *
     *                                                                             *
     * *************************************************************************** *
     *                                                                             *
     * Titre          :                                                            *
     *                                                                             *
     * URL            : http://www.phpsources.org/scripts385-PHP.htm               *
     * Auteur         : Olravet                                                    *
     * Date édition   : 07 Mai 2008                                                *
     * Website auteur : http://olravet.fr/                                         *
     *                                                                             *
     * *************************************************************************** */

    /**
     * Calcul du lever et coucher du soleil n'importe quand et où
     * 
     * @param int $jour Jour pour lequel on souhaite le Lever/Coucher (par défaut jour actuel)
     * @param int $mois Mois pour lequel on souhaite le Lever/Coucher (par défaut mois actuel)
     * @param double $La Latitude de la ville pour le Lever/Coucher (par défaut Latitude Paris)
     * @param double $Lo Longitude de la ville pour le Lever/Coucher (par défaut Longitude Paris)
     * 
     * @return mixed Array avec les valeurs dans les clefs ['lever'] et ['coucher'] 
     */
    public static function getLeverCoucher($jour = null, $mois = null, $La = 48.833, $Lo = -2.333) {
        $fh = date("H") - gmdate("H");
        if (is_null($jour)) {
            $jour = date("d");
        }
        if (is_null($mois)) {
            $mois = date("m");
        }
        // Fuseau horaire et coordonnées géographiques
        $k = 0.0172024;
        $jm = 308.67;
        $jl = 21.55;
        $e = 0.0167;
        $ob = 0.4091;
        $PI = 3.1415926536;
        //Hauteur du soleil au lever et au coucher
        $dr = $PI / 180;
        $hr = $PI / 12;
        $ht = (-40 / 60) * $dr;
        $La = $La * $dr;
        $Lo = $Lo * $dr;
        //Date
        if ($mois < 3) {
            $mois = $mois + 12;
        }
        //Heure TU du milieu de la journée
        $h = 12 + ($Lo / $hr);
        //Nombre de jours écoulés depuis le 1 Mars O h TU
        $J = floor(30.61 * ($mois + 1)) + $jour + ($h / 24) - 123;
        //Anomalie et longitude moyenne
        $M = $k * ($J - $jm);
        $L = $k * ($J - $jl);
        //Longitude vraie
        $S = $L + 2 * $e * Sin($M) + 1.25 * $e * $e * Sin(2 * $M);
        //Coordonnées rectangulaires du soleil dans le repère équatorial
        $X = Cos($S);
        $Y = Cos($ob) * Sin($S);
        $Z = Sin($ob) * Sin($S);
        //Equation du temps et déclinaison
        $R = $L;
        $rx = Cos($R) * $X + Sin($R) * $Y;
        $ry = -Sin($R) * $X + Cos($R) * $Y;
        $X = $rx;
        $Y = $ry;
        $ET = atan($Y / $X);
        $DC = atan($Z / Sqrt(1 - $Z * $Z));
        //Angle horaire au lever et au coucher
        $cs = (Sin($ht) - Sin($La) * Sin($DC)) / Cos($La) / Cos($DC);
        if ($cs > 1) {
            $CalculSol = "Ne se lève pas";
        }
        if ($cs < -1) {
            $CalculSol = "Ne se couche pas";
        }
        if ($cs == 0) {
            $ah = $PI / 2;
        } else {
            $ah = atan(Sqrt(1 - $cs * $cs) / $cs);
        }
        if ($cs < 0) {
            $ah = $ah + $PI;
        }
        //Lever du soleil
        $Pm = $h + $fh + ($ET - $ah) / $hr;
        if ($Pm < 0) {
            $Pm = $Pm + 24;
        }
        if ($Pm > 24) {
            $Pm = $Pm - 24;
        }
        $hs = floor($Pm);
        $Pm = floor(60 * ($Pm - $hs));
        if (strlen($hs) < 2) {
            $hs = "0" . $hs;
        }
        if (strlen($Pm) < 2) {
            $Pm = "0" . $Pm;
        }
        $lev = $hs . ":" . $Pm;
        //Coucher du soleil
        $Pm = $h + $fh + ($ET + $ah) / $hr;
        if ($Pm > 24) {
            $Pm = $Pm - 24;
        }
        if ($Pm < 0) {
            $Pm = $Pm + 24;
        }
        $hs = floor($Pm);
        $Pm = floor(60 * ($Pm - $hs));
        if (strlen($hs) < 2) {
            $hs = "0" . $hs;
        }
        if (strlen($Pm) < 2) {
            $Pm = "0" . $Pm;
        }
        $couch = $hs . ":" . $Pm;
        return array(
            'lever' => $lev,
            'coucher' => $couch,
        );
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

/**
 * Case de l'agenda de réservation, peut être vide (= sans réservation) ou avec une réservation 
 *
 * @category  Classes
 * @name      CaseAgenda
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class CaseAgenda {

    /**
     * Indique si c'est une case dans une zone de réservation interdite (samedi/dimanche)
     * @var bool  
     */
    public $interdit;

    /**
     * Indique si on peut cliquer ou non (case réservable ou réservation modifiable)
     * @var bool   
     */
    public $cliquable;

    /**
     * Indique si la case est vide (true) ou possède une réservation (false)
     * @var bool 
     */
    public $libre;

    /**
     * Indique si il ne faut pas afficher cette case (car dépendante d'un rowspan)
     * @var bool  
     */
    public $masquer;

    /**
     * Nom du pilote ayant réservé
     * @var string 
     */
    public $nom;

    /**
     * Infobulle à afficher
     * @var string   
     */
    public $infobulle;

    /**
     * Indique si l'utilisateur a les droits de modification sur la réservation
     * @var bool 
     */
    public $editable;

    /**
     * Code Jour sous forme AAAAMMJJ
     * @var string 
     */
    public $code_jour;

    /**
     * Indique si il s'agit de la nuit aéro
     * @var bool 
     */
    public $nuit;

    /**
     * Id de la réservation affiché
     * @var int 
     */
    public $resa_id;

    /**
     * Indique si il s'agit d'une réservation de l'aéroclub (true) ou non (false)
     * @var bool 
     */
    public $est_resa_club;

    /**
     * Hauteur du rowspan d'une réservation
     * @var int 
     */
    public $rowspan;

    /**
     * Nom tel qu'il sera affiché de l'instructeur associé à la réservation
     * @var string 
     */
    public $instructeur;

    /**
     * Construit une nouvelle case d'agenda 
     */
    public function __construct() {
        $this->cliquable = false;
        $this->code_jour = '';
        $this->editable = false;
        $this->est_resa_club = false;
        $this->infobulle = '';
        $this->interdit = false;
        $this->libre = false;
        $this->masquer = false;
        $this->nom = '';
        $this->nuit = false;
        $this->resa_id = null;
        $this->rowspan = 1;
        $this->instructeur = '';
    }

}

?>
