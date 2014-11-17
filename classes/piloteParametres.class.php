<?php

/**
 * Public Class PiloteParametre
 * Store the parameters of the Plugin
 * Give some usefull function to show details about an SQL table.
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
 * Class to store parameters of the plugin Pilote
 *
 * @category  Classes
 * @name      PiloteParametre
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteParametre {

    const TABLE = 'parametres';
    const PK = 'code';
    const PARAM_DERNIER_IMPORT = 'DATE_DERNIER_IMPORT';
    const PARAM_COULEUR_RESERVE = 'COULEUR_RESERVE';
    const PARAM_COULEUR_LIBRE = 'COULEUR_LIBRE';
    const PARAM_COULEUR_INTERDIT = 'COULEUR_INTERDIT';
    const PARAM_CALENDRIER_HEURE_DEBUT = 'CALENDRIER_HEURE_DEBUT';
    const PARAM_CALENDRIER_HEURE_FIN = 'CALENDRIER_HEURE_FIN';
    const PARAM_CALENDRIER_HEURE_SAMEDI = 'CALENDRIER_HEURE_SAMEDI';
    const PARAM_CALENDRIER_HEURE_DIMANCHE = 'CALENDRIER_HEURE_DIMANCHE';
    const PARAM_AUTORISER_RESA_INTERDIT = 'AUTORISER_RESA_INTERDIT';
    const PARAM_AEROCLUB_LATITUDE = 'AEROCLUB_LATITUDE';
    const PARAM_AEROCLUB_LONGITUDE = 'AEROCLUB_LONGITUDE';
    const PARAM_CODE_AEROCLUB = 'AD_DEFAUT';
    const PARAM_COTISATION_SECTION = 'COTISATION_SECTION';
    const PARAM_BLOCAGE_ACTIF = 'BLOCAGE_RESERVATION';
    const PARAM_BLOCAGE_MESSAGE_WARNING = 'BLOCAGE_MESSAGE_WARNING';
    const PARAM_BLOCAGE_MESSAGE_BLOQUE = 'BLOCAGE_MESSAGE_BLOQUE';
    const PARAM_INSTRUCTEUR_RESERVATION = 'INSTRUCTEUR_RESA';

    private $_fields = array(
        '_parametre_id' => 'int',
        '_code' => 'string',
        '_libelle' => 'string',
        '_est_date' => 'bool',
        '_valeur_date' => 'date',
        '_est_texte' => 'bool',
        '_valeur_texte' => 'string',
        '_est_numerique' => 'bool',
        '_nombre_decimale' => 'int',
        '_valeur_numerique' => 'double',
        '_date_creation' => 'datetime',
        '_date_modification' => 'datetime'
    );
    private $_parametre_id;
    private $_code;
    private $_libelle;
    private $_est_date;
    private $_valeur_date;
    private $_est_texte;
    private $_valeur_texte;
    private $_est_numerique;
    private $_nombre_decimale;
    private $_valeur_numerique;
    private $_date_creation;
    private $_date_modification;
    private static $_valeurs_parametres = array();
    private static $_libelle_parametres = array();

    /**
     * Indique si le paramètre est un paramètre de couleur
     * 
     * @return bool 
     */
    public function estCouleur() {
        return substr($this->_valeur_texte, 0, 1) == '#';
    }

    /**
     * Construit un paramètre vierge ou depuis son code
     * 
     * @param string|object $args Nom du paramètre ou ligne de BDD
     * 
     * @return PiloteParametre
     */
    public function __construct($args = null) {
        global $zdb;

        if (is_string($args) && strlen($args) > 0) {
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                        ->where(array('code' => $args));
                $result = $zdb->execute($select);
                if ($result->count() == 1) {
                    $this->_loadFromRS($result->current());
                }
                $this->_code = $args;
            } catch (Exception $e) {
                Analog\Analog::log("Erreur" . $e->getMessage(), Analog\Analog::ERROR);
                return false;
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
        $this->_parametre_id = $r->parametre_id;
        $this->_code = $r->code;
        $this->_libelle = $r->libelle;
        $this->_est_date = $r->est_date;
        $this->_valeur_date = $r->valeur_date;
        $this->_est_texte = $r->est_texte;
        $this->_valeur_texte = $r->valeur_texte;
        $this->_est_numerique = $r->est_numerique;
        $this->_nombre_decimale = $r->nombre_decimale;
        $this->_valeur_numerique = $r->valeur_numerique;
        $this->_date_creation = $r->date_creation;
        $this->_date_modification = $r->date_modification;
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
            if (!$this->_valeur_date || $this->_valeur_date == '') {
                $values['valeur_date'] = new Zend\Db\Sql\Predicate\Expression('NULL');
            }

            $values['date_modification'] = date('Y-m-d H:i:s');

            if (!isset($this->_parametre_id) || $this->_parametre_id == '') {
                $values['date_creation'] = date('Y-m-d H:i:s');
                $insert = $zdb->insert(PILOTE_PREFIX . self::TABLE)
                        ->values($values);
                $add = $zdb->execute($insert);
                if ($add > 0) {
                    $this->_parametre_id = $zdb->driver->getLastGeneratedValue();
                }
            } else {
                $update = $zdb->update(PILOTE_PREFIX . self::TABLE)
                        ->set($values)
                        ->where(array(self::PK => $this->_code));
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
     * Renvoie la valeur d'un paramètre à partir de son code
     * 
     * @param string $code Code du paramètre
     * 
     * @return type Peut être du texte, une date ou une valeur numérique
     */
    public static function getValeurParametre($code) {
        global $zdb;

        if (array_key_exists($code, self::$_valeurs_parametres)) {
            return self::$_valeurs_parametres[$code];
        } else {
            Analog\Analog::log('Get all parameters from BDD', Analog\Analog::DEBUG);
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE);
                $parametres = $zdb->execute($select);
                foreach ($parametres as $p) {
                    self::_metEnCacheParametre($p);
                }
                return self::$_valeurs_parametres[$code];
            } catch (Exception $e) {
                Analog\Analog::log("Erreur" . $e->getMessage(), Analog\Analog::ERROR);
                return false;
            }
        }
    }

    /**
     * Renvoi le libellé d'un paramètre
     * 
     * @param string $code Nom du paramètre
     * 
     * @return string Valeur du libellé associé au nom du paramètre
     */
    public static function getLibelleParametre($code) {
        global $zdb;

        if (array_key_exists($code, self::$_libelle_parametres)) {
            Analog\Analog::log('Get libelle ' . $code . ' from cache ;-)', Analog\Analog::DEBUG);
            return self::$_libelle_parametres[$code];
        } else {
            Analog\Analog::log('Get libelle ' . $code . ' from BDD :-(', Analog\Analog::DEBUG);
            try {
                $select = $zdb->select(PILOTE_PREFIX . self::TABLE);

                $parametres = $zdb->execute($select);
                foreach ($parametres as $p) {
                    self::_metEnCacheParametre($p);
                }
                return self::$_libelle_parametres[$code];
            } catch (Exception $e) {
                Analog\Analog::log("Erreur" . $e->getMessage(), Analog\Analog::ERROR);
                return false;
            }
        }
    }

    /**
     * Renvoie la liste des codes utilisés dans l'application
     * 
     * @return array Tableau des codes utilisés pour les paramètres
     */
    public static function getTousCodesParametres() {
        global $zdb;

        $code = self::PK;
        try {
            $select = $zdb->select(PILOTE_PREFIX . self::TABLE)
                    ->columns(array($code))
                    ->order('1');
            $rows = $zdb->execute($select);
            $liste_codes = array();
            foreach ($rows as $row) {
                $liste_codes[] = $row->$code;
            }
            return $liste_codes;
        } catch (Exception $e) {
            Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
            return false;
        }
    }

    /**
     * Teste l'existence d'une table en base
     * 
     * @param string $nom_table Nom de la table dont on souhaite tester l'existence
     * 
     * @return bool Renvoie true/false selon que la table existe ou non
     */
    public static function tableExiste($nom_table) {
        global $zdb;

        /**
         * Query pour voir si la table existe ou non
         */
        try {
            $metadata = new \Zend\Db\Metadata\Metadata($zdb->db);
            $tmp_tables_list = $metadata->getTableNames();
            foreach ($tmp_tables_list as $table_name) {
                if ($table_name == $nom_table) {
                    return true;
                }
            }
        } catch (Exception $e) {
            Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
            return false;
        }
        return false;
    }

    /**
     * Compte le nombre d'enregistrements dans la table indiquée (fait un SELECT COUNT(*) sur la table)
     * 
     * @param string $nom_table Nom de la table
     * 
     * @return int Nombre d'enregistrement
     */
    public static function nbEnregistrementTable($nom_table) {
        global $zdb;

        /**
         * Query pour connaitre le nombre d'enregistrement
         */
        try {
            $select = $zdb->select($nom_table)
                    ->columns(array('nb' => new Zend\Db\Sql\Predicate\Expression('count(*)')));
            $result = $zdb->execute($select);
            return $result->current()->nb;
        } catch (Exception $e) {
            Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
            return false;
        }
    }

    /**
     * Cherche la version de la table (colonne nommée version_{version}) et renvoi cette version si trouvée
     * 
     * @param string $nom_table Nom de la table
     * 
     * @return string Version de la table
     */
    public static function versionTable($nom_table) {
        global $zdb;

        try {
            $metadata = new \Zend\Db\Metadata\Metadata($zdb->db);
            $table = $metadata->getTable($nom_table);
            $columns = $table->getColumns();
            foreach ($columns as $col) {
                $colname = $col->getName();
                if (strpos($colname, 'version') !== FALSE) {
                    return str_replace('version_', '', $colname);
                }
            }
            return 'N/A';
        } catch (Exception $e) {
            Analog\Analog::log('Erreur SQL ' . $e->getMessage(), Analog\Analog::ERROR);
            return false;
        }
    }

    /**
     * Met la valeur d'un paramètre en cache pour ne le lire qu'une fois par page.
     * 
     * @param PiloteParametre $parametre Le paramètre dont on veut avoir la valeur en cache.
     */
    private static function _metEnCacheParametre($parametre) {
        if ($parametre->est_date) {
            if ($parametre->valeur_date !== NULL) {
                $dt = date_create_from_format('Y-m-d', $parametre->valeur_date);
                self::$_valeurs_parametres[$parametre->code] = $dt->format('d/m/Y');
                self::$_libelle_parametres[$parametre->code] = $parametre->libelle;
            }
        } else if ($parametre->est_texte) {
            self::$_valeurs_parametres[$parametre->code] = $parametre->valeur_texte;
            self::$_libelle_parametres[$parametre->code] = $parametre->libelle;
        } else if ($parametre->est_numerique) {
            self::$_valeurs_parametres[$parametre->code] = $parametre->valeur_numerique;
            self::$_libelle_parametres[$parametre->code] = $parametre->libelle;
        }
    }

    /**
     * Ecrit la pagination d'une liste selon le nombre d'objets total dans la liste, le tri et
     * la direction définis. Renvoi la pagination au format HTML prêt à être inséré dans la page
     * finale.
     * 
     * @param int $no_page N° actuel de la page vue pour mise en surbrillance
     * @param string $tri Valeur de la variable de tri choisi
     * @param string $direction Valeur de la direction du tri choisi
     * @param int $nb_objet Nombre d'objets au total dans la liste
     * @param int $nb_lignes Nombre d'enregistrements par page
     * @param string $complement Complément à mettre dans l'URL (faire précéder d'un "&")
     * 
     * @return string La pagination faite
     */
    public static function faitPagination($no_page, $tri, $direction, $nb_objet, $nb_lignes, $complement) {
        if ($nb_objet < $nb_lignes) {
            return '';
        }

        $pagination = '<a href="?tri=' . $tri . '&direction=' . $direction . '&page=1&nb_lignes=' . $nb_lignes . $complement . '" title="Atteindre la rremière page">|&lt;&lt;</a> &#xB7; ';
        for ($i = 0; $i < ceil($nb_objet / $nb_lignes); $i++) {
            if ($no_page == $i) {
                $pagination .= '<b>';
            }
            $pagination .= '<a href="?tri=' . $tri . '&direction=' . $direction . '&page=' . $i . '&nb_lignes=' . $nb_lignes . $complement . '" title="Atteindre la page ' . ($i + 1) . '">' . ($i + 1) . '</a>';
            if ($no_page == $i) {
                $pagination .= '</b>';
            }
            $pagination .= ' &#xB7; ';
        }
        $pagination .= '<a href="?tri=' . $tri . '&direction=' . $direction . '&page=' . ceil($nb_objet / $nb_lignes) . '&nb_lignes=' . $nb_lignes . $complement . '" title="Atteindre la dernière page">&gt;&gt;|</a> ';

        return $pagination;
    }

    /**
     * Eclaircit une couleur donnée en héxa décimal (#rrggbb) et renvoie le résultat
     * 
     * @param string $color couleur en héxa (#rrggbb)
     * @param int $factor facteur d'éclaircissement
     * 
     * @return string la nouvelle couleur #rrggbb plus claire
     */
    public static function eclaircirCouleurHexa($color, $factor = 30) {
        $color = str_replace('#', '', $color);
        $new_hex = '#';

        $base['R'] = hexdec($color{0} . $color{1});
        $base['G'] = hexdec($color{2} . $color{3});
        $base['B'] = hexdec($color{4} . $color{5});

        foreach ($base as $k => $v) {
            $amount = 255 - $v;
            $amount = $amount / 100;
            $amount = round($amount * $factor);
            $new_decimal = $v + $amount;

            $new_hex_component = dechex($new_decimal);
            if (strlen($new_hex_component) < 2) {
                $new_hex_component = "0" . $new_hex_component;
            }
            $new_hex .= $new_hex_component;
        }

        return $new_hex;
    }

    /**
     * Assombrit une couleur donnée en héxa décimal (#rrggbb) et renvoie le résultat
     * 
     * @param string $color couleur en héxa (#rrggbb)
     * @param int $dif facteur d'assombrissement
     * 
     * @return string la nouvelle couleur #rrggbb plus sombre
     */
    public static function assombrirCouleur($color, $dif = 30) {

        $color = str_replace('#', '', $color);
        if (strlen($color) != 6) {
            return '000000';
        }
        $rgb = '';

        for ($x = 0; $x < 3; $x++) {
            $c = hexdec(substr($color, (2 * $x), 2)) - $dif;
            $c = ($c < 0) ? 0 : dechex($c);
            $rgb .= (strlen($c) < 2) ? '0' . $c : $c;
        }

        return '#' . $rgb;
    }

}
