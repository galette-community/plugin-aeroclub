<?php

/**
 * Public Class PiloteOperation
 * Store information about flights and operations of a pilot.
 * Money in and out
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
 * Class to store operations of the member : flights and bills
 *
 * @category  Classes
 * @name      PiloteOperation
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteOperation {

    const TABLE = "operations";
    const PK = "operation_id";

    private $_fields = array(
        '_operation_id' => 'integer',
        '_id_adherent' => 'integer',
        '_access_exercice' => 'integer',
        '_access_id' => 'integer',
        '_date_operation' => 'date',
        '_immatriculation' => 'string',
        '_duree_minute' => 'integer',
        '_type_vol' => 'string',
        '_aeroport_depart' => 'string',
        '_aeroport_arrivee' => 'string',
        '_instructeur' => 'string',
        '_nb_atterrissages' => 'integer',
        '_type_operation' => 'string',
        '_libelle_operation' => 'string',
        '_montant_operation' => 'double',
        '_sens' => 'char',
        '_nb_passagers' => 'integer',
        '_indicateur_forfait' => 'bool',
        '_code_forfait' => 'string',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime'
    );
    private $_operation_id;
    private $_id_adherent;
    private $_access_exercice;
    private $_access_id;
    private $_date_operation;
    private $_immatriculation;
    private $_duree_minute;
    private $_type_vol;
    private $_aeroport_depart;
    private $_aeroport_arrivee;
    private $_instructeur;
    private $_nb_atterrissages;
    private $_type_operation;
    private $_libelle_operation;
    private $_montant_operation;
    private $_sens;
    private $_nb_passagers;
    private $_indicateur_forfait = 0;
    private $_code_forfait = '';
    // Utilisé pour numéroter les lignes sur l'IHM
    private $_numero_ligne;
    // Utilisé pour calcul heure/minute
    private $_duree_heure;
    // Utilisé pour modifier une opération
    private $_date_operation_IHM;
    private $_date_creation;
    private $_date_modification;

    /**
     * Construit une nouvelle opération vierge où à partir de son ID ou couple ExerciceComptable_AccessID
     * 
     * @param int|string|object $args Un ID, une string "Exercice_ID" ou une ligne de BDD
     */
    public function __construct($args = null) {
        global $zdb;

        if (is_int($args)) {
            try {
                $select = new Zend_Db_Select($zdb->db);
                $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                        ->where(self::PK . ' = ?', $args);
                if ($select->query()->rowCount() == 1) {
                    $this->_loadFromRS($select->query()->fetch());
                }
            } catch (Execption $e) {
                Analog\Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog\Analog::ERROR
                );
            }
        } else if (is_string($args)) {
            list($exercice, $ope_id) = explode('_', $args);
            $this->_access_exercice = intval($exercice);
            $this->_access_id = intval($ope_id);
            try {
                $select = new Zend_Db_Select($zdb->db);
                $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                        ->where('access_exercice = ?', intval($exercice))
                        ->where('access_id = ?', intval($ope_id));
                if ($select->query()->rowCount() == 1) {
                    $this->_loadFromRS($select->query()->fetch());
                }
            } catch (Execption $e) {
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
        $this->_operation_id = $r->operation_id;
        $this->_id_adherent = $r->id_adherent;
        $this->_access_exercice = $r->access_exercice;
        $this->_access_id = $r->access_id;
        $this->_date_operation = $r->date_operation;
        $this->_immatriculation = $r->immatriculation;
        $this->_duree_minute = $r->duree_minute;
        $this->_type_vol = $r->type_vol;
        $this->_aeroport_depart = $r->aeroport_depart;
        $this->_aeroport_arrivee = $r->aeroport_arrivee;
        $this->_instructeur = $r->instructeur;
        $this->_nb_atterrissages = $r->nb_atterrissages;
        $this->_type_operation = $r->type_operation;
        $this->_libelle_operation = $r->libelle_operation;
        $this->_montant_operation = $r->montant_operation;
        $this->_sens = $r->sens;
        $this->_nb_passagers = $r->nb_passagers;
        $this->_indicateur_forfait = $r->indicateur_forfait;
        $this->_code_forfait = $r->code_forfait;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
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

            $this->_sens = $this->_montant_operation > 0 ? 'C' : 'D';

            foreach ($this->_fields as $k => $v) {
                $values[substr($k, 1)] = $this->$k;
            }

            //an empty value will cause date to be set to 1901-01-01, a null
            //will result in 0000-00-00. We want a database NULL value here.
            if (!$this->_date_operation || $this->_date_operation == '') {
                $values['date_operation'] = new Zend_Db_Expr('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_operation_id) || $this->_operation_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $add = $zdb->db->insert(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values);
                if ($add > 0) {
                    $this->_operation_id = $zdb->db->lastInsertId();
                    // logging
                    // trop verbeux, commenté
                    //$hist->add(_T("OPERATION.AJOUT SUCCES"), strtoupper($this->_login_adherent));
                } else {
                    $hist->add(_T("OPERATION.AJOUT ECHEC"));
                    throw new Exception(_T("OPERATION.AJOUT ECHEC"));
                }
            } else {
                $edit = $zdb->db->update(
                        PREFIX_DB . PILOTE_PREFIX . self::TABLE, $values, self::PK . '=' . $this->_operation_id
                );
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
                if ($edit > 0) {
                    $hist->add(_T("OPERATION.MISE A JOUR"), strtoupper($this->_id_adherent));
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
     * Renvoie un ID unique pour la colonne Access qui ne sera pas concurent avec les imports Access.
     * L'ID sera > 1.000.000
     * 
     * @param int $access_exercice Année d'exercice 
     * 
     * @return int Renvoie l'Id suivant disponible selon l'exercice comptable donné
     */
    public static function getNextAccessId($access_exercice) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'access_id')
                    ->where('access_exercice = ?', $access_exercice)
                    ->order('1 desc')
                    ->limitPage(1, 1);
            $result = $select->query()->fetch();
            if (intval($result->access_id) < 1000000) {
                return 1000000;
            } else {
                return intval($result->access_id) + 1;
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
     * Renvoi le nombre d'opérations enregistrées sur l'utilisateur indiqué pour l'année choisie
     * 
     * @param string $login Login de l'utilisateur
     * @param int $annee Année (facultatif)
     * 
     * @return int Nombre d'opérations enregistrées sur l'utilisateur
     */
    public static function getNombreOperationsForLogin($login, $annee = -1) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login);
            if ($annee > 0) {
                $select->where('YEAR(date_operation) = ?', $annee);
            }
            return $select->query()->rowCount();
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoie toutes les opérations pour le login donné. Filtre par année si elle est fournit.
     * 
     * @param string $login Login de l'utilisateur dont on cherche les opérations
     * @param string $tri Colonne sur laquelle le tri ASC/DESC doit être effectué
     * @param string $direction Sens de tri (ASC/DESC)
     * @param int $no_page N° de la page de résultat à afficher
     * @param int $lignes_par_page Nombre de lignes à afficher par page
     * @param int $annee Année pour laquelle afficher les opérations
     * 
     * @return PiloteOperation Un tableau d'opérations pour le pseudo indiqué 
     */
    public static function getOperationsForLogin($login, $tri, $direction, $no_page, $lignes_par_page, $annee = -1) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->order($tri . ' ' . $direction)
                    ->limitPage($no_page, $lignes_par_page);
            if ($annee > 0) {
                $select->where('YEAR(date_operation) = ?', $annee);
            }

            $operations = array();
            $result = $select->query()->fetchAll();
            for ($i = 0; $i < count($result); $i++) {
                $piloteOperation = new PiloteOperation($result[$i]);
                $piloteOperation->numero_ligne = $i;
                $operations[] = $piloteOperation;
            }
            return $operations;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi le nombre de vols sur l'année sélectionnée correspondant à l'utilisateur indiqué
     * 
     * @param string $login Login de l'utilisateur dont on souhaite le nombre de vols
     * @param int $annee Année (facultatif)
     * 
     * @return int Nombre de vols pour cette année et cet utilisateur
     */
    public static function getNombreVolsForLogin($login, $annee = -1) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('duree_minute is not null');
            if ($annee > 0) {
                $select->where('YEAR(date_operation) = ?', $annee);
            }
            return $select->query()->rowCount();
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoie tous les vols pour le login donné. Filtre par année si elle est fournit.
     * 
     * @param string $login Login de l'utilisateur dont on cherche les opérations
     * @param string $tri Colonne sur laquelle le tri ASC/DESC doit être effectué
     * @param string $direction Sens de tri (ASC/DESC)
     * @param int $no_page N° de la page de résultat à afficher
     * @param int $lignes_par_page Nombre de lignes à afficher par page
     * @param int $annee Année pour laquelle afficher les opérations
     * 
     * @return PiloteOperation Un tableau des vols pour le pseudo indiqué 
     */
    public static function getVolsForLogin($login, $tri, $direction, $no_page, $lignes_par_page, $annee = -1) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('duree_minute is not null')
                    ->order($tri . ' ' . $direction)
                    ->limitPage($no_page, $lignes_par_page);
            if ($annee > 0) {
                $select->where('YEAR(date_operation) = ?', $annee);
            }

            $operations = array();
            $result = $select->query()->fetchAll();
            for ($i = 0; $i < count($result); $i++) {
                $piloteOperation = new PiloteOperation($result[$i]);
                $piloteOperation->numero_ligne = $i;
                $operations[] = $piloteOperation;
            }
            return $operations;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la date sous forme textuelle du dernier vol de l'utilisateur donné en paramètre
     * 
     * @param string $login Login de l'adhérent dont on souhaite la date de dernier vol
     * 
     * @return boolean|string False si échec, sinon string représentant la date (ex: 1 
     */
    public static function getDernierVolForLogin($login) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'max(date_operation) as dernier_vol')
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('duree_minute is not null');
            if ($select->query()->rowCount() == 1) {
                $dt = new DateTime($select->query()->fetch()->dernier_vol);
                return $dt->format('j M Y');
            }
            return '';
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoie le solde opérationnel d'un membre pour une année donnée.
     * Si l'année n'est pas précisée, prend l'année en cours.
     * 
     * @param string $login Login du membre pour lequel on veut le solde.
     * @param int $annee Année pour laquelle on souhaite le solde (si non spécifié, année en cours)
     * @return decimal Solde du membre
     */
    public static function getSoldeCompteForLogin($login, $annee = -1) {
        global $zdb;

        if ($annee < 0) {
            $annee = date('Y');
        }
        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(montant_operation) as somme')
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('year(' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.date_operation) = ?', $annee);
            if ($select->query()->rowCount() == 1) {
                //$name = 'sum(montant_operation)';
                return $select->query()->fetch()->somme;
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
     * Renvoi le temps de vol cumulé en minute sur l'année spécifiée d'un utilisateur par son login
     * 
     * @param string $login Identifiant de l'utilisateur
     * @param int $annee Année sur laquelle calculer le temps de vol
     * 
     * @return boolean|int Nombre de minutes de temps de vol
     */
    public static function getTempsVolForLogin($login, $annee) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(duree_minute) as duree')
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('duree_minute is not null')
                    ->where('YEAR(date_operation) = ?', $annee);
            if ($select->query()->rowCount() == 1) {
                return $select->query()->fetch()->duree;
            }
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
        }
        return false;
    }

    /**
     * Renvoi le nombre d'atterrissages d'un adhérent au cours des 3 derniers mois
     * 
     * @param string $login Identifiant de l'utilisateur
     * 
     * @return int|boolean Le nombre d'atterrisages de l'adhérent au cours des 3 derniers mois
     */
    public static function getNombreAtterrissageTroisDerniersMois($login) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(nb_atterrissages) as sum')
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('duree_minute is not null')
                    ->where('date_operation > date_sub(now(), interval 3 month)');
            if ($select->query()->rowCount() == 1) {
                $nb_atterr = $select->query()->fetch()->sum;
                if ($nb_atterr == null) {
                    $nb_atterr = 0;
                }
                return $nb_atterr;
            }
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
        }
        return false;
    }

    /**
     * Renvoi le temps de vol cumulé sur la dernière année glissante pour un utilisateur spécifié
     * 
     * @param string $login Login de l'utilisateur dont on souhaite le temps de vol cumulé
     * 
     * @return boolean|int Le temps de vol sur une année glissante en minute
     */
    public static function getTempsVolAnneeGlissanteForLogin($login) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, 'sum(duree_minute) as duree')
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $pseudo)
                    ->where('date_operation > ?', date('Y-m-d', strtotime('-1 year')));
            if ($select->query()->rowCount() == 1) {
                return $select->query()->fetch()->duree;
            }
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
        }
        return false;
    }

    /**
     * Renvoi le montant des opérations, ainsi que la durée de vol et la dernière date d'opération
     * d'un membre pour le mois donné sur une année flottante. 
     * 
     * @param string $login Login du membre
     * @param int $mois Mois (entre 1 et 12)
     * @return object Un objet avec 3 propriétées montant (montant des opérations), duree (durée de vol), max (dernière date d'opération)
     */
    public static function getMontantOperationForMonth($login, $mois) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $cols = array();
            $cols[] = 'sum(montant_operation) as montant';
            $cols[] = 'sum(duree_minute) as duree';
            $cols[] = 'max(date_operation) as max';
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $cols)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh = ?', $login)
                    ->where('date_operation >= ?', date('Y-m-01', strtotime('+1 months -1 years')))
                    ->where('month(date_operation) = ?', $mois);
            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() == 1) {
                return $select->query()->fetch();
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
        /*
          select sum(montant_operation) as 'somme',
          from vav_pilote_operations
          join vav_adherents on vav_adherents.id_adh = vav_pilote_operations.id_adherent
          where date_operation > date_sub(now(), interval 1 year)
          and vav_adherents.login_adh = '121TA'
          and month(date_operation) = 1
         */
    }

    /**
     * Renvoi la liste des types de vol pour l'année indiquée ou sur toutes les opérations
     * si aucune année indiquée
     * 
     * @param int $annee Année sur laquelle filtrer pour les types de vols, ou rien
     * 
     * @return boolean|string[] Liste des types de vols pour l'année ou tous
     */
    public static function getTypesVols($annee = '') {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('type_vol'))
                    ->where('duree_minute is not null')
                    ->group('type_vol')
                    ->order('1');
            if (strlen($annee) == 4) {
                $select->where('year(date_operation) = ?', $annee);
            }
            if ($select->query()->rowCount() > 0) {
                $result = array();
                $rows = $select->query()->fetchAll();
                foreach ($rows as $r) {
                    $result[] = $r->type_vol;
                }
                return $result;
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
     * Renvoi la liste des types d'opération existant dans la base
     * 
     * @return boolean|string[] Liste ordonnée des types d'opération
     */
    public static function getTypesOperations() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE, array('type_operation'))
                    ->group('type_operation')
                    ->order('1');
            $result = array();
            $rows = $select->query()->fetchAll();
            foreach ($rows as $r) {
                $result[] = $r->type_operation;
            }
            return $result;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Calcul les statistiques d'heures de vol pour tous les membres sur l'année en cours, l'année
     * N-1 et l'année flottante (sur les 12 derniers mois)
     * 
     * @param string $typevol Type de vol filtré ('all' pour n'importe quel type)
     * @param string $tri Ordre de tri des résultats
     * @param string $direction Sens de tri ASC / DESC
     * @return array Tableau de membres avec les propriétées supplémentaires somme2011 (année N-1), somme2012 (année en cours), sommeglissant (sur 12 derniers mois)
     */
    public static function getStatistiquesPilotes($typevol = 'all', $tri = 'nom_adh', $direction = 'asc') {
        global $zdb;

        /*
         * SELECT id_adh AS idcalcul, nom_adh, prenom_adh, login_adh, (
          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND year( date_operation ) =2011
          ) AS somme2011, (

          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND year( date_operation ) =2012
          ) AS somme2012, (

          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND date_operation > '2011-06-01'
          ) AS sommeglissant
          FROM vav_adherents
          WHERE activite_adh =1
         */

        try {
            $select2011 = new Zend_Db_Select($zdb->db);
            $select2011->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('id_adherent = idcalcul')
                    ->where('year(date_operation) = ?', date('Y', strtotime('-1 years')));
            if ($typevol != 'all') {
                $select2011->where('type_vol = ?', $typevol);
            }

            $select2012 = new Zend_Db_Select($zdb->db);
            $select2012->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('id_adherent = idcalcul')
                    ->where('year(date_operation) = ?', date('Y'));
            if ($typevol != 'all') {
                $select2012->where('type_vol = ?', $typevol);
            }

            $selectglissant = new Zend_Db_Select($zdb->db);
            $selectglissant->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('id_adherent = idcalcul')
                    ->where('date_operation > ?', date('Y-m-d', strtotime('-1 years')));
            if ($typevol != 'all') {
                $selectglissant->where('type_vol = ?', $typevol);
            }

            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . Galette\Entity\Adherent::TABLE, array('*',
                        'idcalcul' => 'id_adh',
                        'somme2011' => new Zend_Db_Expr('(' . $select2011 . ')'),
                        'somme2012' => new Zend_Db_Expr('(' . $select2012 . ')'),
                        'sommeglissant' => new Zend_Db_Expr('(' . $selectglissant . ')')))
                    ->where('activite_adh = 1')
                    ->order($tri . ' ' . $direction);

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                return $select->query()->fetchAll();
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi le nombre d'heures de vols par type de vols pour l'année donnée
     * pour tous les adherents actifs qui ont effectués des heures de vols
     * sur l'année donnée.
     * 
     * @param int $annee Année du vol
     * @param string $tri Ordre de tri
     * @param string $direction Sens de tri
     * @return mixed Tableau de résultats
     */
    public static function getTypeVolsHeuresPilotes($annee, $tri = 'nom_adh', $direction = 'asc') {
        global $zdb;

        try {
            if (strlen($annee) < 4) {
                $annee = date('Y');
            }

            // $types_vol nous permet de récupérer les colonnes qui l'on cherche
            $types_vol = array();
            $types_vol[] = '*';
            $types_vol['idcalcul'] = 'id_adh';
            // On récupère d'abord les types de vols sur l'année
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('type_vol'))
                    ->where('year(date_operation) = ?', $annee)
                    ->where('duree_minute is not null')
                    ->group('type_vol')
                    ->order('1');
            // On créé autant de requête SQL que de types vols existant
            $rows = $select->query()->fetchAll();
            for ($i = 0; $i < count($rows); $i++) {
                if (!is_null($rows[$i]->type_vol)) {
                    $selectTV = new Zend_Db_Select($zdb->db);
                    $selectTV->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                            ->where('id_adherent = idcalcul')
                            ->where('year(date_operation) = ?', $annee)
                            ->where('type_vol = ?', $rows[$i]->type_vol);
                    $types_vol['TV' . $i] = new Zend_Db_Expr('(' . $selectTV . ')');
                }
            }
            // On ajoute une requête pour la somme sur l'année
            $selectTV = new Zend_Db_Select($zdb->db);
            $selectTV->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('id_adherent = idcalcul')
                    ->where('year(date_operation) = ?', $annee);
            $types_vol['TVtotal'] = new Zend_Db_Expr('(' . $selectTV . ')');

            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . Galette\Entity\Adherent::TABLE, $types_vol)
                    ->where('activite_adh = 1')
                    ->order($tri . ' ' . $direction);

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                $results = array();
                $rows = $select->query()->fetchAll();
                foreach ($rows as $r) {
                    if (is_numeric($r->TVtotal)) {
                        $results[] = $r;
                    }
                }
                return $results;
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi les statistiques de nombre d'heures de vol pour les avions pour l'année en cours,
     * l'année précédente et les 12 derniers mois.
     * 
     * @param string $tri Ordre de tri des résultats
     * @param string $direction Sens de tri ASC/DESC
     * @return array Tableau des avions avec les propriétées supplémentaires somme2011 (année N-1), somme2012 (année en cours), sommeglissant (sur 12 derniers mois)
     */
    public static function getStatistiquesAvions($tri = 'immatriculation', $direction = 'asc') {
        global $zdb;

        /*
         * SELECT `immatriculation` AS immat, (

          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND year( date_operation ) =2011
          ) AS somme2011, (

          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND year( date_operation ) =2012
          ) AS somme2012, (

          SELECT sum( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND date_operation > '2011-06-01'
          ) AS sommeglisse
          FROM `vav_pilote_operations`
          GROUP BY immatriculation
         */

        try {
            $select2011 = new Zend_Db_Select($zdb->db);
            $select2011->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('immatriculation = immat')
                    ->where('year(date_operation) = ?', date('Y', strtotime('-1 years')));

            $select2012 = new Zend_Db_Select($zdb->db);
            $select2012->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('immatriculation = immat')
                    ->where('year(date_operation) = ?', date('Y'));

            $selectglissant = new Zend_Db_Select($zdb->db);
            $selectglissant->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, 'sum(duree_minute)')
                    ->where('immatriculation = immat')
                    ->where('date_operation > ?', date('Y-m-d', strtotime('-1 years')));

            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array(
                        'immat' => 'immatriculation',
                        'somme2011' => new Zend_Db_Expr('(' . $select2011 . ')'),
                        'somme2012' => new Zend_Db_Expr('(' . $select2012 . ')'),
                        'sommeglissant' => new Zend_Db_Expr('(' . $selectglissant . ')')
                    ))
                    ->where('immatriculation is not null')
                    ->where('duree_minute is not null')
                    ->group('immatriculation')
                    ->order($tri . ' ' . $direction);

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                return $select->query()->fetchAll();
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la liste des immatriculations pour lesquelles des opérations ont été enregistrées
     * dans la table des opérations
     * 
     * @return array Tableau des immatriculations sur lesquelles des opérations existent
     */
    public static function getAvionsActifs() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('immatriculation'))
                    ->where('duree_minute is not null')
                    ->where('immatriculation is not null')
                    ->group('immatriculation')
                    ->order('immatriculation');

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                $avions = array();
                $rows = $select->query()->fetchAll();
                foreach ($rows as $r) {
                    $avions[] = $r->immatriculation;
                }
                return $avions;
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la durée de vol d'une immatriculation pour le mois et l'année indiqués
     * 
     * @param string $immatriculation Immatriculation pour laquelle on veut la durée de vol
     * @param int $mois Mois (de 1 à 12)
     * @param int $annee Année (ex: 2011, 2012, etc.)
     * @return decimal Durée de vol en minutes
     */
    public static function getDureeVolPourAvion($immatriculation, $mois, $annee) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('somme' => 'sum(duree_minute)'))
                    ->where('immatriculation = ?', $immatriculation)
                    ->where('month(date_operation) = ?', $mois)
                    ->where('year(date_operation) = ?', $annee);

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() == 1) {
                return $select->query()->fetch()->somme;
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la durée de vol d'une immatriculation pour le mois et l'année indiqués
     * 
     * @param int $mois Mois (de 1 à 12)
     * @param int $annee Année (ex: 2011, 2012, etc.)
     * @return decimal Durée de vol en minutes
     */
    public static function getDureeVolPourTousAvions($mois, $annee) {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('somme' => 'sum(duree_minute)'))
                    ->where('month(date_operation) = ?', $mois)
                    ->where('year(date_operation) = ?', $annee);

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() == 1) {
                return $select->query()->fetch()->somme;
            }
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la liste des adhérents actifs triés par la propriété indiquées (nom par défaut)
     * 
     * @param type $orderby
     * 
     * @return boolean|SQL Tableau des lignes SQL des adhérents actifs (objet avec noms de colonnes)
     */
    public static function getAdherentsActifs($orderby = 'nom_adh') {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . Galette\Entity\Adherent::TABLE)
                    ->where('activite_adh = 1')
                    ->order($orderby);
            $result = $select->query()->fetchAll();

            $liste_adherents = array();
            foreach ($result as $row) {
                $liste_adherents[] = $row;
            }
            return $liste_adherents;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la liste des années pour lesquelles des opérations sont enregistrées
     * 
     * @return array Tableau des années sur lesquelles des données existent
     */
    public static function getAnneesOperations() {
        global $zdb;

        try {
            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('annee' => 'year(date_operation)'))
                    ->where('montant_operation is not null')
                    ->group('year(date_operation)')
                    ->order('1');

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                $result = array();
                $rows = $select->query()->fetchAll();
                foreach ($rows as $r) {
                    $result[] = $r->annee;
                }
                return $result;
            }

            /**
             * SELECT year( date_operation ) AS annee
             * FROM vav_pilote_operations
             * WHERE montant_operation IS NOT NULL
             * GROUP BY year( date_operation )
             * ORDER BY 1 
             */
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Renvoi la liste des pilotes avec leur 
     * 
     * @param bool $actif Permet d'avoir les pilotes actifs ou non (null pour tous)
     * @param string $solde Permet d'avoir les soldes négatif/zéro/positif/tous uniquement (negatif/zero/positif/all)
     * @param string $tri Colonne de tri
     * @param string $direction Sens de tri (asc/desc)
     * @return array 
     */
    public static function getSoldesPilotes($actif, $solde, $tri, $direction) {
        global $zdb;

        try {
            $selectsolde = new Zend_Db_Select($zdb->db);
            $selectsolde->from(PREFIX_DB . PILOTE_PREFIX . self::TABLE, array('sum(montant_operation)'))
                    ->where(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where('year(date_operation) = ?', date('Y'));

            $select = new Zend_Db_Select($zdb->db);
            $select->from(PREFIX_DB . Galette\Entity\Adherent::TABLE, array(
                        'nom_adh',
                        'prenom_adh',
                        'login_adh',
                        'email_adh',
                        'solde' => new Zend_Db_Expr('(' . $selectsolde . ')'),
                        'id_adh',
                    ))
                    ->order($tri . ' ' . $direction);
            if (!is_null($actif)) {
                $select->where('activite_adh = ?', $actif);
            }

            //Analog\Analog::log($select->assemble(), Analog\Analog::INFO);
            if ($select->query()->rowCount() > 0) {
                $result = array();
                $rows = $select->query()->fetchAll();
                foreach ($rows as $r) {
                    if ($solde == 'negatif' && is_numeric($r->solde) && $r->solde < 0) {
                        $result[] = $r;
                    } else if ($solde == 'zero' && is_numeric($r->solde) && $r->solde == 0) {
                        $result[] = $r;
                    } else if ($solde == 'positif' && is_numeric($r->solde) && $r->solde > 0) {
                        $result[] = $r;
                    } else if ($solde == 'all') {
                        $result[] = $r;
                    }
                }

                return $result;
            }

            /**
             * SELECT * , 
             *  (select sum(montant_operation) 
             *   from `vav_pilote_operations` 
             *   where `vav_pilote_operations`.id_adherent = vav_adherents.id_adh
             *   and year(date_operation)=2012) as solde
             *   FROM `vav_adherents` 
             *   WHERE `activite_adh` = 1
             *  
             */
            return false;
        } catch (Exception $e) {
            Analog\Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog\Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Supprime toutes les opérations pour l'année donnée dont les ID ne sont pas listés
     * 
     * @param array $operations_id Tableau des IDs des opérations à NE PAS supprimer
     * @param int $annee Année sur laquelle supprimer les opérations
     * @return int Nombre d'opérations supprimées
     */
    public static function deleteObsoleteOperation($operations_id, $annee) {
        global $zdb;

        try {
            //$zdb->db->getProfiler()->setEnabled(true);

            $where = array();
            $where[] = $zdb->db->quoteInto('access_exercice = ?', $annee);
            $where[] = 'access_id NOT IN (' . join(',', $operations_id) . ')';
            $n = $zdb->db->delete(PREFIX_DB . PILOTE_PREFIX . self::TABLE, $where);
            //Analog\Analog::log($zdb->db->getProfiler()->getLastQueryProfile()->getQuery(), Analog\Analog::INFO);
            //$zdb->db->getProfiler()->setEnabled(false);

            return $n;
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
            case 'date_operation_court':
                if ($this->date_operation != '') {
                    $d = new DateTime($this->date_operation);
                    return $d->format('j') . ' ' . _T('COMPTE VOL.MOIS.' . $d->format('m'));
                } else {
                    return '';
                }
            case 'date_operation':
                if ($this->$rname != '') {
                    $d = new DateTime($this->$rname);
                    return $d->format('j M Y');
                } else {
                    return '';
                }
            case 'duree':
                if ($this->_duree_minute) {
                    $heure = floor($this->_duree_minute / 60);
                    $minute = $this->_duree_minute - $heure * 60;
                    if ($heure > 0) {
                        return $heure . ' h ' . $minute . ' min';
                    } else {
                        return $minute . ' min';
                    }
                } else {
                    return '';
                }
            case 'montant':
                return number_format($this->_montant_operation, 2, ',', ' ') . ' EUR';
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
