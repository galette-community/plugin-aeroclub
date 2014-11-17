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
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\Operator;
use Analog\Analog;

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
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                        ->where(array(self::PK => $args));
                $result = $zdb->execute($select);
                if ($result->count() == 1) {
                    $this->_loadFromRS($result->current());
                }
            } catch (Execption $e) {
                Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog::ERROR
                );
            }
        } else if (is_string($args)) {
            list($exercice, $ope_id) = explode('_', $args);
            $this->_access_exercice = intval($exercice);
            $this->_access_id = intval($ope_id);
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                        ->where(array('access_exercice' => intval($exercice), 'access_id' => intval($ope_id)));
                $result = $zdb->execute($select);
                if ($result->count() == 1) {
                    $this->_loadFromRS($result->current());
                }
            } catch (Execption $e) {
                Analog::log(
                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                        $e->getTraceAsString(), Analog::ERROR
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
                $values['date_operation'] = new Expression('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_operation_id) || $this->_operation_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $insert = $zdb->insert(PILOTE_PREFIX . self::TABLE)
                        ->values($values);
                $add = $zdb->execute($insert);
                if ($add > 0) {
                    $this->_operation_id = $zdb->driver->getLastGeneratedValue();
                } else {
                    $hist->add(_T("OPERATION.AJOUT ECHEC"));
                    throw new Exception(_T("OPERATION.AJOUT ECHEC"));
                }
            } else {
                $update = $zdb->update(PILOTE_PREFIX . self::TABLE)
                        ->set($values)
                        ->where(array(self::PK => $this->_operation_id));
                $edit = $zdb->execute($update);
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
                if ($edit > 0) {
                    $hist->add(_T("OPERATION.MISE A JOUR"), strtoupper($this->_operation_id));
                }
            }
            return true;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('access_id'))
                    ->where(array('access_exercice' => $access_exercice))
                    ->order('access_id desc')
                    ->limit(1);

            $result = $zdb->execute($select);
            if (intval($result->current()->access_id) < 1000000) {
                return 1000000;
            } else {
                return intval($result->current()->access_id) + 1;
            }
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $where = array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login);
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('nb' => new Expression('count(*)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent');
            if ($annee > 0) {
                $where[] = 'YEAR(date_operation) = ' . $annee;
            }
            $select->where($where);

            $result = $zdb->execute($select);
            return $result->current()->nb;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $where = array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login);
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->order(array($tri . ' ' . $direction, 'type_operation asc'))
                    ->limit($lignes_par_page)
                    ->offset($no_page * $lignes_par_page);
            if ($annee > 0) {
                $where[] = 'YEAR(date_operation) = ' . $annee;
            }
            $select->where($where);

            $operations = array();
            $result = $zdb->execute($select);
            foreach ($result as $r) {
                $operations[] = new PiloteOperation($r);
            }
            return $operations;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $where = array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, new IsNotNull('duree_minute'));
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('nb' => new Expression('count(*)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent');
            if ($annee > 0) {
                $where[] = 'YEAR(date_operation) = ' . $annee;
            }
            $select->where($where);

            $result = $zdb->execute($select);
            return $result->current()->nb;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $where = array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, new IsNotNull('duree_minute'));
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->order($tri . ' ' . $direction)
                    ->limit($lignes_par_page)
                    ->offset($no_page * $lignes_par_page);
            if ($annee > 0) {
                $where[] = 'YEAR(date_operation) = ' . $annee;
            }
            $select->where($where);

            $operations = array();
            $result = $zdb->execute($select);
            foreach ($result as $r) {
                $operations[] = new PiloteOperation($r);
            }
            return $operations;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . PiloteOperation::TABLE)
                    ->columns(array('dernier_vol' => new Expression('MAX(date_operation)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, new IsNotNull('duree_minute')));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                $dt = new DateTime($result->current()->dernier_vol);
                return $dt->format('j M Y');
            }
            return '';
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('somme' => new Expression('SUM(montant_operation)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, 'YEAR(' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.date_operation) = ' . $annee));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current()->somme;
            }
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . PiloteOperation::TABLE)
                    ->columns(array('duree' => new Expression('SUM(duree_minute)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, new IsNotNull('duree_minute'), 'YEAR(date_operation) = ' . $annee));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current()->duree;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $where = array();
            $where[PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh'] = $login;
            $where[] = new IsNotNull('duree_minute');
            $where[] = new Operator('date_operation', Operator::OP_GT, new Expression('date_sub(now(), interval 3 month)'));
            $select = $zdb->select(PILOTE_PREFIX . PiloteOperation::TABLE)
                    ->columns(array('sum' => new Expression('SUM(nb_atterrissages)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where($where);

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                $nb_atterr = $result->current()->sum;
                return $nb_atterr == null ? 0 : $nb_atterr;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . PiloteOperation::TABLE)
                    ->columns(array('duree' => new Expression('SUM(duree_minute)')))
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . PiloteOperation::TABLE . '.id_adherent')
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, new Expression('date_operation > \'' . date('Y-m-d', strtotime('-1 year')) . '\'')));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current()->duree;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $cols = array();
            $cols['montant'] = new Expression('SUM(montant_operation)');
            $cols['duree'] = new Expression('SUM(duree_minute)');
            $cols['max'] = new Expression('MAX(date_operation)');
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns($cols)
                    ->join(PREFIX_DB . Galette\Entity\Adherent::TABLE, PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent')
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.login_adh' => $login, 'date_operation >= \'' . date('Y-m-01', strtotime('+1 months -1 years')) . '\'', 'MONTH(date_operation) = ' . $mois));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current();
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
            );
            return false;
        }
        /*
          select SUM(montant_operation) as 'somme',
          from vav_pilote_operations
          join vav_adherents on vav_adherents.id_adh = vav_pilote_operations.id_adherent
          where date_operation > date_sub(now(), interval 1 year)
          and vav_adherents.login_adh = '121TA'
          and MONTH(date_operation) = 1
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
            $where = array(new IsNotNull('duree_minute'));
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('type_vol'))
                    ->group('type_vol')
                    ->order('type_vol');
            if (strlen($annee) == 4) {
                $where[] = 'YEAR(date_operation) = ' . $annee;
            }
            $select->where($where);

            $result = $zdb->execute($select);
            $type_vols = array();
            foreach ($result as $r) {
                $type_vols[] = $r->type_vol;
            }
            return $type_vols;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . PiloteOperation::TABLE)
                    ->columns(array('type_operation'))
                    ->group('type_operation')
                    ->order('type_operation');
            $type_ops = array();

            $rows = $zdb->execute($select);
            foreach ($rows as $r) {
                $type_ops[] = $r->type_operation;
            }
            return $type_ops;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
     * @return array Tableau de membres avec les propriétées supplémentaires somme_last_year (année N-1), somme_this_year (année en cours), somme_glissant (sur 12 derniers mois)
     */
    public static function getStatistiquesPilotes($typevol = 'all', $tri = 'nom_adh', $direction = 'asc') {
        global $zdb;

        /*
         * SELECT id_adh AS idcalcul, nom_adh, prenom_adh, login_adh, (
          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND YEAR( date_operation ) =2011
          ) AS somme2011, (

          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND YEAR( date_operation ) =2012
          ) AS somme2012, (

          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE id_adherent = idcalcul
          AND date_operation > '2011-06-01'
          ) AS sommeglissant
          FROM vav_adherents
          WHERE activite_adh =1
         */

        try {
            $where_last_year = array();
            $where_last_year[] = 'id_adherent = idcalcul';
            $where_last_year[new Expression('YEAR(date_operation)')] = date('Y', strtotime('-1 years'));

            $where_this_year = array();
            $where_this_year[] = 'id_adherent = idcalcul';
            $where_this_year[new Expression('YEAR(date_operation)')] = date('Y');

            $where_glissant = array();
            $where_glissant[] = 'id_adherent = idcalcul';
            $where_glissant[] = 'YEAR(date_operation) > ' . date('Y-m-d', strtotime('-1 years'));

            if ($typevol != 'all') {
                $where_last_year ['type_vol'] = $typevol;
                $where_this_year['type_vol'] = $typevol;
                $where_glissant['type_vol'] = $typevol;
            }

            $select_last_year = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where($where_last_year);

            $select_this_year = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where($where_this_year);

            $select_12_months = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where($where_glissant);

            $columns = array();
            $columns[] = '*';
            $columns['idcalcul'] = 'id_adh';
            $columns['somme_last_year'] = new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_last_year) . ')');
            $columns['somme_this_year'] = new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_this_year) . ')');
            $columns['somme_glissant'] = new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_12_months) . ')');
            $select = $zdb->select(Galette\Entity\Adherent::TABLE)
                    ->columns($columns)
                    ->where(array('activite_adh' => '1'))
                    ->order($tri . ' ' . $direction);

            $adherent_sommes = array();
            $results = $zdb->execute($select);
            foreach ($results as $r) {
                $adherent_sommes[] = $r;
            }
            return $adherent_sommes;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('type_vol'))->where(array('YEAR(date_operation) = ' . $annee, new IsNotNull('duree_minute')))
                    ->group('type_vol')
                    ->order('type_vol');
// On créé autant de requête SQL que de types vols existant
            $rows = $zdb->execute($select);
            $i = 0;
            foreach ($rows as $r) {
                if (!is_null($r->type_vol)) {
                    $selectTV = $zdb->select(PILOTE_PREFIX . self::TABLE)
                            ->columns(array('duree' => new Expression('SUM(duree_minute)')))
                            ->where(array('id_adherent = idcalcul', 'YEAR(date_operation) = ' . $annee, 'type_vol' => $r->type_vol));
                    $query = $zdb->sql->getSqlStringForSqlObject($selectTV);
                    $types_vol['TV' . $i] = new Expression('(' . $query . ')');
                }
                $i ++;
            }
// On ajoute une requête pour la somme sur l'année
            $selectTV = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('duree' => new Expression('SUM(duree_minute)')))
                    ->where(array('id_adherent = idcalcul', 'YEAR(date_operation) = ' . $annee));
            $query = $zdb->sql->getSqlStringForSqlObject($selectTV);
            $types_vol['TVtotal'] = new Expression('(' . $query . ')');

            $select = $zdb->select(Galette\Entity\Adherent::TABLE)
                    ->columns($types_vol)
                    ->where(array('activite_adh' => 1))
                    ->order($tri . ' ' . $direction);

            $rows = $zdb->execute($select);
            $results = array();
            foreach ($rows as $r) {
                if (is_numeric($r->TVtotal)) {
                    $results[] = $r;
                }
            }
            return $results;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
    public static function getStatistiquesAvions
    ($tri = 'immatriculation', $direction = 'asc') {
        global $zdb;

        /*
         * SELECT `immatriculation` AS immat, (

          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND YEAR( date_operation ) =2011
          ) AS somme2011, (

          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND YEAR( date_operation ) =2012
          ) AS somme2012, (

          SELECT SUM( duree_minute )
          FROM vav_pilote_operations
          WHERE immatriculation = immat
          AND date_operation > '2011-06-01'
          ) AS sommeglisse
          FROM `vav_pilote_operations`
          GROUP BY immatriculation
         */

        try {
            $select_last_year = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where(array('immatriculation = immat', new Expression('YEAR(date_operation)') => date('Y', strtotime('-1 years'))));

            $select_this_year = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where('immatriculation = immat')
                    ->where(array('immatriculation = immat', new Expression('YEAR(date_operation)') => date('Y')));

            $select_12_months = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(new Expression('SUM(duree_minute)')))
                    ->where('immatriculation = immat')
                    ->where(array('immatriculation = immat', 'YEAR(date_operation) > ' . date('Y-m-d', strtotime('-1 years'))));

            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array(
                        'immat' => 'immatriculation',
                        'somme_last_year' => new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_last_year) . ')'),
                        'somme_this_year' => new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_this_year) . ')'),
                        'somme_glissant' => new Expression('(' . $zdb->sql->getSqlStringForSqlObject($select_12_months) . ')')
                    ))
                    ->where(array(new IsNotNull('immatriculation'), new IsNotNull('duree_minute')))
                    ->group('immatriculation')
                    ->order($tri . ' ' . $direction);

            $results = $zdb->execute($select);
            if ($results->count() > 0) {
                return $results;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('immatriculation'))
                    ->where(array
                        (new IsNotNull('duree_minute'), new IsNotNull('immatriculation')))
                    ->group('immatriculation')
                    ->order('immatriculation');

            $avions = array();
            $rows = $zdb->execute($select);
            foreach ($rows as $r) {
                $avions[] = $r->immatriculation;
            }
            return $avions;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
    public static function getDureeVolPourAvion(
    $immatriculation, $mois, $annee) {
        global $zdb;

        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('somme' => new Expression('SUM(duree_minute)')))
                    ->where(array('immatriculation' => $immatriculation, 'MONTH(date_operation) = ' . $mois, 'YEAR(date_operation) = ' . $annee));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current()->somme;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('somme' => new Expression
                                ('SUM(duree_minute)')))
                    ->where(array('MONTH(date_operation) = ' . $mois, 'YEAR(date_operation) = ' . $annee));

            $result = $zdb->execute($select);
            if ($result->count() == 1) {
                return $result->current()->somme;
            }
            return false;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(Galette\Entity\Adherent::TABLE)
                    ->where(array('activite_adh' => 1))->
                    order($orderby);
            $result = $zdb->execute($select);

            $liste_adherents = array();
            foreach ($result as $row) {
                $liste_adherents[] = $row;
            }
            return $liste_adherents;
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('annee' => new Expression('YEAR(date_operation)')))
                    ->where(new IsNotNull('montant_operation'))
                    ->group('annee')
                    ->order('annee');

            $result = array();
            $rows = $zdb->execute($select);
            foreach ($rows as $r) {
                $result[] = $r->annee;
            }
            return $result;

            /**
             * SELECT YEAR( date_operation ) AS annee
             * FROM vav_pilote_operations
             * WHERE montant_operation IS NOT NULL
             * GROUP BY YEAR( date_operation )
             * ORDER BY 1 
             */
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
    public static function getSoldesPilotes(
    $actif, $solde, $tri, $direction) {
        global $zdb;

        try {
            $selectsolde = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array('somme' => new Expression('SUM(montant_operation)')))
                    ->where(array(PREFIX_DB . Galette\Entity\Adherent::TABLE . '.id_adh = ' . PREFIX_DB . PILOTE_PREFIX . self::TABLE . '.id_adherent', 'YEAR(date_operation) = ' . date('Y')));
            $query = $zdb->sql->getSqlStringForSqlObject($selectsolde);

            $select = $zdb->select(Galette\Entity\Adherent::TABLE)
                    ->columns(array(
                        'nom_adh',
                        'prenom_adh',
                        'login_adh',
                        'email_adh',
                        'solde' => new Expression('(' . $query . ')'),
                        'id_adh',
                    ))
                    ->order($tri . ' ' . $direction);
            if (!is_null($actif)) {
                $select->where(array('activite_adh' => $actif));
            }

            $result = array();
            $rows = $zdb->execute($select);
            foreach ($rows as $r) {
                if ($solde == 'negatif' && is_numeric($r->solde) && $r->solde < 0) {
                    $result[] = $r;
                } else if ($solde == 'zero' && is_numeric($r->solde) && $r->solde == 0) {
                    $result [] = $r;
                } else if ($solde == 'positif' && is_numeric($r->solde) && $r->solde > 0) {
                    $result [] = $r;
                } else if ($solde == 'all') {
                    $result[] = $r;
                }
            }

            return $result;

            /**
             * SELECT * , 
             *  (select SUM(montant_operation) 
             *   from `vav_pilote_operations` 
             *   where `vav_pilote_operations`.id_adherent = vav_adherents.id_adh
             *   and YEAR(date_operation)=2012) as solde
             *   FROM `vav_adherents` 
             *   WHERE `activite_adh` = 1
             *  
             */
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
    public static function

    deleteObsoleteOperation($operations_id, $annee) {
        global $zdb;

        try {
            $delete = $zdb->delete(PILOTE_PREFIX . self::TABLE)
                    ->where(array('access_exercice' => $annee, 'access_id NOT IN (' . join(',', $operations_id) . ')'));
            return $zdb->execute($delete);
        } catch (Exception $e) {
            Analog::log(
                    'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                    $e->getTraceAsString(), Analog::ERROR
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
