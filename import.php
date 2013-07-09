<?php

/**
 * Special import function from an very specific ACCESS 2000 program.
 * Very specific and only usable with this ACCESS 2000 program.
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
 * Transforme une date au format Access 2 en date au format string classique ou string SQL
 * 
 * @param string $str Date à traduire
 * @param bool $sql Indique si la date doit être formatée au format SQL 'AAAA-MM-JJ' ou standard 'JJ/MM/AAAA'
 * 
 * @return La date au bon format
 */
function dateAccessToString($str, $sql = false) {
    if (strlen($str) < 4) {
        return $sql ? new Zend_Db_Expr('NULL') : '';
    }
    

    // j = Jour du mois sans les zéros initiaux
    // n = Mois sans les zéros initiaux
    // Y = Année sur 4 chiffres
    // H = Heure, au format 24h, avec les zéros initiaux 
    // i = Minutes avec les zéros initiaux 
    // s = Secondes, avec zéros initiaux
    $dt = date_create_from_format("j/n/Y H:i:s", $str);
    // L'export se fait désormais avec une date sur 4 chiffres. 
    // Plus la peine de vérifier la cohérence de date
    //if (intval($dt->format("Y")) > 2040) {
    //    $dt = $dt->sub(new DateInterval('P100Y'));
    //}
    if ($sql) {
        return $dt->format("Y-m-d");
    } else {
        return $dt->format("d/m/Y");
    }
}

/**
 * Transforme une date du format JJ/MM/AAAA au format AAAA-MM-JJ
 * 
 * @param string $str Une date au format JJ/MM/AAAA
 * 
 * @return string Une date au format AAAA-MM-JJ
 */
function dateIHMtoSQL($str) {
    if (strlen($str) < 5) {
        return '';
    }
    $dt = date_create_from_format('d/m/Y', $str);
    return $dt->format('Y-m-d');
}

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
if (!$login->isLogged() || !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

$tpl->assign('page_title', _T("IMPORT.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

// Définition des variables
$file_imported = false;
$table_created = false;
$choix_annee = false;
$fichier_deja_importe = false;
$liste_annees = array();
$name_uploaded_file = '';
$filtre_import = array_key_exists('filter', $_POST) && $_POST['filter'] == 'vrai';
$ignore_section = array_key_exists('ignore_section', $_POST) && $_POST['ignore_section'] == 'vrai';
$row = -1;

/**
 * IMPORT 
 */
if (array_key_exists('import', $_POST) && array_key_exists('pilote_import_file', $_FILES)) {

// Variable globale qui sert à atteindre la base de donnée et exécuter les requêtes
    global $zdb;

    /**
     * Montée du temps de traitement de l'import à 10 min.
     */
    set_time_limit(10 * 60);

    /**
     * Copie de sauvegarde pour historiser 
     */
    if (!is_dir('historique_import')) {
        mkdir('historique_import');
    }
    $wep_exp_import = 'historique_import/WEB_EXP_' . date('Y-m-d_H-i') . '.txt';
    copy($_FILES['pilote_import_file']['tmp_name'], $wep_exp_import);

    /**
     * Ouverture du fichier
     */
    $f = fopen($_FILES['pilote_import_file']['tmp_name'], 'r');
    $f_log = fopen('historique_import/import_log_' . date('Y-m-d_H-i') . '.txt', 'a');
    fwrite($f_log, date("j M H:i:s") . " | -- DEBUT -- \n");

    $adherents_login_id = array();

    $date_dernier_import = new PiloteParametre(PiloteParametre::PARAM_DERNIER_IMPORT);
    fwrite($f_log, date("j M H:i:s") . " | DATE DERNIER IMPORT : " . PiloteParametre::getValeurParametre(PiloteParametre::PARAM_DERNIER_IMPORT) . "\n");
    fwrite($f_log, date("j M H:i:s") . " | IMPORT FILTRE SUR DATE : " . ($filtre_import ? "OUI" : "NON") . "\n");
    fwrite($f_log, date("j M H:i:s") . " | SECTION DU SITE : " . $preferences->pref_slogan . "\n");

    /**
     * Import des données dans les tables
     */
    $row = 0;
    $ligne_mise_a_jour = 0;
    $nb_adherent_crees = 0;
    $nb_adherent_modifies = 0;
    $nb_operation_creees = 0;
    $nb_operation_modifiees = 0;
    $exercice_courant = -1;
    $code_tresorier = '';
    $id_operations = array();
    while (($line = fgetcsv($f, 1000, ';', '"')) !== FALSE) {
        if ($row > 0) {
            switch ($line[0]) {
                case "EXPORT":
                    // EXPORT signifie description entête de table 
                    // Rien à faire
                    break;
                case "GENERAL":
                    /**
                     * Table GENERAL
                     */
                    if (!$ignore_section && $preferences->pref_slogan != utf8_encode($line[2])) {
                        die('Erreur de section. Section attendue ' . $preferences->pref_slogan .
                                '. Section trouvée : ' . utf8_encode($line[2]));
                    }
                    $preferences->pref_nom = utf8_encode($line[1]);
                    $preferences->pref_email_nom = utf8_encode($line[1] . ' / ' . $line[2]);
                    $preferences->pref_card_strip = utf8_encode($line[1] . ' / ' . $line[2]);
                    $preferences->pref_slogan = utf8_encode($line[2]);
                    $preferences->pref_adresse = utf8_encode($line[3]);
                    $preferences->pref_cp = utf8_encode($line[6]);
                    $preferences->pref_ville = utf8_encode($line[4]);
                    $preferences->pref_pays = 'France';
                    $preferences->pref_email = $line[11];
                    $preferences->pref_email_newadh = $line[11];
                    $preferences->pref_card_year = $line[9];
                    $exercice_courant = intval($line[9]);
                    $preferences->store();
                    fwrite($f_log, date("j M H:i:s") . " | SECTION D'IMPORT : " . $line[2] . "\n");
                    fwrite($f_log, date("j M H:i:s") . " | EXERCICE D'IMPORT : " . $exercice_courant . "\n");
                    break;
                case "MEMBRES" :

                    if (!$filtre_import || dateAccessToString($line[27], true) >= dateIHMtoSQL(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_DERNIER_IMPORT))) {
                        /**
                         * Création ou mise à jour de l'adhérent
                         */
                        $adherent = new Galette\Entity\Adherent();
                        $adherent->loadFromLoginOrMail($line[1]);
                        // L'adresse sur 2 lignes est séparée par @#@
                        $addr2 = '';
                        if (strpos($line[4], '@#@') !== false) {
                            list($addr1, $addr2) = preg_split('/@#@/', utf8_encode(trim($line[4])));
                        } else {
                            $addr1 = trim($line[4]);
                        }
                        $import_values = array(
                            'id_adh' => '',
                            'pseudo_adh' => $line[1],
                            'login_adh' => $line[1],
                            'nom_adh' => utf8_encode(trim($line[2])),
                            'prenom_adh' => utf8_encode(trim($line[3])),
                            'adresse_adh' => $addr1,
                            'adresse2_adh' => $addr2,
                            'cp_adh' => $line[5],
                            'ville_adh' => utf8_encode(trim($line[6])),
                            'pays_adh' => 'France',
                            'email_adh' => trim($line[7]),
                            'ddn_adh' => dateAccessToString($line[8]),
                            'titre_adh' => $line[9] ? 1 : 2,
                            'tel_adh' => trim($line[10]),
                            'gsm_adh' => trim($line[11]),
                            'date_echeance' => dateAccessToString($line[18]),
                            'activite_adh' => false,
                            'id_statut' => 4,
                            'bool_display_info' => 0,
                            'pref_lang' => 'fr_FR',
                            'info_adh' => str_replace('@#@', '\n', utf8_encode(trim($line[25]))),
                            'info_public_adh' => str_replace('@#@', '\n', utf8_encode(trim($line[25]))),
                        );

                        if (is_numeric($adherent->id) && intval($adherent->id) > 0) {
                            $import_values['id_adh'] = $adherent->id;
                            // bug 31 - bug 41 - bug 51 - anciennes valeurs écrasées
                            $import_values['activite_adh'] = $adherent->isActive();
                            $import_values['id_statut'] = $adherent->status;
                            $import_values['bool_display_info'] = $adherent->appearsInMembersList();
                            $nb_adherent_modifies++;
                        } else {
                            $nb_adherent_crees++;
                        }
                        if (strlen($adherent->login) > 0) {
                            $import_values['login_adh'] = $adherent->login;
                        }

                        //les champs désactivés (les mêmes que sur self_adherent) - optionnel (à priori)
                        $inactifs = array(); // $pilote->disabled_fields + $pilote->edit_disabled_fields;
                        //les champs requis - optionnel (à priori) - gérés par une classe à part
                        $fc = new Galette\Entity\FieldsConfig(Galette\Entity\Adherent::TABLE, $adherent->fields);
                        $requis = $fc->getRequired();

                        if (strlen($adherent->password) <= 0) {
                            $import_values['mdp_adh'] = $line[1];
                            $import_values['mdp_adh2'] = $line[1];
                        } else {
                            unset($requis['mdp_adh']);
                        }
                        if (strlen($adherent->creation_date) == 0) {
                            $dt = new DateTime();
                            $import_values['date_crea_adh'] = $dt->format("d/m/Y");
                        } else {
                            $import_values['date_crea_adh'] = $adherent->creation_date;
                        }

                        //stockage des valeurs dans l'objet et vérifications (formatage des dates, etc, etc)
                        $ok = $adherent->check($import_values, $requis, $inactifs);

                        if ($ok) {
                            $adherent->store();
                        }
                        if (is_array($ok)) {
                            fwrite($f_log, date("j M H:i:s") . "******* ERREUR MEMBRES | " . $line[1] . " | " . trim($line[2]) . " " . trim($line[3]) . "\n");
                            foreach ($ok as $l) {
                                fwrite($f_log, date("j M H:i:s") . "===========> DETAIL ERREUR | " . $l . "\n");
                            }
                            foreach ($import_values as $k => $v) {
                                fwrite($f_log, date("j M H:i:s") . "===========> VALEURS ENVOYEES : " . $k . " -> " . $v . "\n");
                            }
                        }

                        // On a un Login et un ID, on stocke pour plus tard
                        $adherents_login_id[$adherent->login] = $adherent->id;

                        /**
                         * Création ou mise à jour du complément pilote spécifique
                         */
                        $complement = PiloteAdherentComplement::findByLogin($adherent->id);

                        $complement->id_adherent = $adherent->id;
                        $complement->tel_travail = trim($line[12]);
                        $complement->no_fax = trim($line[13]);
                        $complement->actif = $line[14] == 1 ? true : false;
                        $complement->est_eleve = $line[15] == 1 ? true : false;
                        $complement->indicateur_journalier = $line[16];
                        $complement->date_dernier_vol = dateAccessToString($line[17], true);
                        $complement->date_visite_medicale = dateAccessToString($line[18], true);
                        $complement->date_fin_license = dateAccessToString($line[19], true);
                        $complement->date_vol_controle = dateAccessToString($line[20], true);
                        $complement->indicateur_forfait = $line[21];
                        $complement->ck_situation_aero = $line[22] == 1 ? true : false;
                        $complement->no_bb = trim($line[23]);
                        $complement->no_ppl = trim($line[24]);
                        $complement->releve_mail = $line[26] == 1 ? true : false;

                        $complement->store();

                        fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | MEMBRES | " . $line[1] . " | " . trim($line[2]) . " " . trim($line[3]) . "\n");
                        $ligne_mise_a_jour++;
                    }
                    break;
                case "OPERATIONS" :
                    list($exerciceAccess, $idOpe) = preg_split('/_/', $line[1]);
                    $id_operations[] = intval($idOpe);
                    if (!$filtre_import || dateAccessToString($line[18], true) >= dateIHMtoSQL(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_DERNIER_IMPORT))) {
                        /**
                         * TABLE DES OPERATIONS
                         */
                        // On regarde si l'opération existe déjà ou non
                        $operation = new PiloteOperation($line[1]);
                        if (is_numeric($operation->operation_id) && $operation->operation_id > 0) {
                            $nb_operation_modifiees++;
                        } else {
                            $nb_operation_creees++;
                        }

                        // On vérifie qu'on a déjà un ID pour notre LOGIN
                        $code_membre = $line[2];
                        if (!array_key_exists($code_membre, $adherents_login_id)) {
                            //$temp_adherent = new Galette\Entity\Adherent($code_membre);
                            //$adherents_login_id[$code_membre] = $temp_adherent->id;
                        }
                        // On stocke les infos dans l'objet
                        $operation->id_adherent = intval($adherents_login_id[$code_membre]);
                        $operation->date_operation = dateAccessToString($line[3], true);
                        $operation->immatriculation = utf8_encode($line[4]);
                        if (strlen($line[5] > 2)) {
                            $dt = date_create_from_format("j/n/Y H:i:s", $line[5]);
                            $operation->duree_minute = intval($dt->format('H')) * 60 + intval($dt->format('i'));
                        }
                        $operation->type_vol = utf8_encode($line[6]);
                        $operation->aeroport_depart = utf8_encode($line[7]);
                        $operation->aeroport_arrivee = utf8_encode($line[8]);
                        $operation->instructeur = utf8_encode($line[9]);
                        $operation->nb_atterrissages = $line[10];
                        $operation->type_operation = utf8_encode($line[11]);
                        $operation->libelle_operation = utf8_encode($line[12]);
                        $operation->montant_operation = str_replace(',', '.', $line[13]);
                        $operation->sens = $line[14];
                        $operation->nb_passagers = $line[15];
                        $operation->indicateur_forfait = $line[16];
                        $operation->code_forfait = $line[17];

                        // On enregistre (insert ou update, pas de soucis)
                        $operation->store();

                        /**
                         * Si le type d'opération est ACM, c'est une cotisation d'adhésion.
                         * On stocke aussi dans la table contribution
                         */
                        if ($operation->type_operation == PiloteParametre::getValeurParametre(PiloteParametre::PARAM_COTISATION_SECTION)) {
                            // Récupération de l'ID adhérent
                            $id_adh = intval($adherents_login_id[$code_membre]);

                            // Préparation des valeurs à insérer dans la table contributions pour la cotisation
                            $values = array(
                                'id_adh' => $id_adh,
                                'id_type_cotis' => 1,
                                'montant_cotis' => 0 - doubleval($operation->montant_operation),
                                'info_cotis' => $operation->libelle_operation,
                                'date_enreg' => dateAccessToString($line[3], true),
                                'date_debut_cotis' => substr(dateAccessToString($line[3], true), 0, 4) . '-01-01',
                                'date_fin_cotis' => substr(dateAccessToString($line[3], true), 0, 4) . '-12-31'
                            );
                            // Insert dans la table des contributions
                            try {
                                $zdb->db->insert(PREFIX_DB . Galette\Entity\Contribution::TABLE, $values);
                            } catch (Exception $e) {
                                Analog\Analog::log(
                                        'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                                        $e->getTraceAsString(), Analog\Analog::ERROR
                                );
                            }

                            // Si la date d'opération est l'année en cours
                            // On met à jour la date_echeance de l'adhérent à la fin de l'année
                            if (intval(substr(dateAccessToString($line[3], true), 0, 4)) == $exercice_courant) {
                                $values = array(
                                    'date_echeance' => substr(dateAccessToString($line[3], true), 0, 4) . '-12-31',
                                    'activite_adh' => true
                                );
                                $zdb->db->getProfiler()->setEnabled(true);
                                try {
                                    $zdb->db->update(PREFIX_DB . Galette\Entity\Adherent::TABLE, $values, Galette\Entity\Adherent::PK . ' = ' . $id_adh);
                                } catch (Exception $e) {
                                    Analog\Analog::log(
                                            'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                                            $e->getTraceAsString(), Analog\Analog::ERROR
                                    );
                                    $query = $zdb->db->getProfiler()->getLastQueryProfile();
                                    Analog\Analog::log(
                                            'CODE MEMBRE : ' . $code_membre .
                                            ' / ID : ' . $adherents_login_id[$code_membre] .
                                            ' / ERREUR IMPORT ECHEANCE: ' . $query->getQuery(), Analog\Analog::ERROR
                                    );
                                }
                                $zdb->db->getProfiler()->setEnabled(false);
                            }
                        }

                        fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | OPERATIONS | " . $line[1] . " | " . number_format($operation->montant_operation, 2) . " | " . $operation->type_operation . " | " . $operation->type_vol . "\n");
                        $ligne_mise_a_jour++;
                    }

                    break;
                case "PARAMETRES" :
                    if (!$filtre_import || dateAccessToString($line[10], true) >= dateIHMtoSQL(PiloteParametre::getValeurParametre(PiloteParametre::PARAM_DERNIER_IMPORT))) {
                        /**
                         * Ligne de PARAMETRES
                         */
                        $parametre = new PiloteParametre($line[1]);
                        $parametre->libelle = utf8_encode($line[2]);
                        $parametre->est_date = $line[3];
                        $parametre->valeur_date = dateAccessToString($line[4], true);
                        $parametre->est_texte = $line[5];
                        $parametre->valeur_texte = $line[6] == '' ? new Zend_Db_Expr('NULL') : utf8_encode($line[6]);
                        $parametre->est_numerique = $line[7];
                        $parametre->nombre_decimale = $line[8];
                        $parametre->valeur_numerique = $line[9] == '' ? new Zend_Db_Expr('NULL') : str_replace(',', '.', $line[9]);
                        $parametre->store();

                        if ($line[1] == 'CODE_TRESORIER') {
                            $code_tresorier = $line[6];
                        }

                        fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | PARAMETRES | " . $line[1] . "\n");
                        $ligne_mise_a_jour++;
                    }
                    break;

                case "AERONEFS" :
                    $avion = new PiloteAvion($line[1]);
                    $avion->immatriculation = $line[1];
                    $avion->marque_type = $line[2];
                    $avion->nom = $line[3];
                    $avion->nom_court = $line[4];
                    $avion->type_aeronef = $line[5];
                    $avion->DC_autorisee = $line[6] == '1' ? true : false;
                    $avion->est_remorqueur = $line[7] == '1' ? true : false;
                    $avion->est_reservable = $line[8] == 'Y' ? true : false;
                    $avion->store();
                    fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | AERONEFS | " . $avion->immatriculation . "\n");
                    $ligne_mise_a_jour++;
                    break;

                case "INSTRUCTEUR" :
                    $instructeur = new PiloteInstructeur($line[1]);
                    $instructeur->code = $line[1];
                    $instructeur->nom = utf8_encode($line[2]);
                    $instructeur->code_adherent = $line[3];
                    if (strlen(trim($line[3])) > 0) {
                        $adherent = new Galette\Entity\Adherent($line[3]);
                        $instructeur->adherent_id = $adherent->id;
                    }
                    $instructeur->store();
                    fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | INSTRUCTEUR | " . $instructeur->code . "\n");
                    $ligne_mise_a_jour++;
                    break;

                case "TYPE_OPERATION" :
                    if ($line[4] == '1') {
                        $param_cot_section = new PiloteParametre(PiloteParametre::PARAM_COTISATION_SECTION);
                        $param_cot_section->valeur_texte = $line[1];
                        $param_cot_section->store();
                        fwrite($f_log, $ligne_mise_a_jour . " | " . date("j M H:i:s") . " | TYPE_OPERATION | " . $line[1] . "\n");
                        $ligne_mise_a_jour++;
                    }
                    break;
            }
        }
        $row++;
    }
    fclose($f);
    fwrite($f_log, date("j M H:i:s") . " | FIN TRAITEMENT FICHIER IMPORT \n");
    fwrite($f_log, date("j M H:i:s") . " | NOUVEAUX MEMBRES : " . $nb_adherent_crees . " \n");
    fwrite($f_log, date("j M H:i:s") . " | MEMBRES MIS A JOUR : " . $nb_adherent_modifies . " \n");
    fwrite($f_log, date("j M H:i:s") . " | NOUVELLES OPERATIONS : " . $nb_operation_creees . " \n");
    fwrite($f_log, date("j M H:i:s") . " | OPERATIONS MISES A JOUR : " . $nb_operation_modifiees . " \n");

    foreach ($adherents_login_id as $k => $v) {
        Analog\Analog::log('LOGINS ' . $k . ' => ' . $v, Analog\Analog::ERROR);
    }

    if (strlen($code_tresorier) > 1) {
        try {
            $zdb->db->update(PREFIX_DB . Galette\Entity\Adherent::TABLE, array('bool_admin_adh' => 1), 'login_adh = "' . $code_tresorier . '"');
        } catch (Exception $exc) {
            Analog\Analog::log('Something went wrong :\'( | ' . $exc->getTraceAsString(), Analog\Analog::ERROR);
        }
    }
    fwrite($f_log, date("j M H:i:s") . " | MISE A JOUR CODE TRESORIER EFFECTUEE \n");

    $f = fopen($wep_exp_import, 'a');
    fwrite($f, '"LIGNE_IMPORT";' . $ligne_mise_a_jour);
    fclose($f);
    fwrite($f_log, date("j M H:i:s") . " | MISE A JOUR FICHIER IMPORT NOMBRE DE LIGNES \n");

    $file_imported = true;

    // Suppression des opérations obsolète
    $delOperats = PiloteOperation::deleteObsoleteOperation($id_operations, $exercice_courant);
    fwrite($f_log, date("j M H:i:s") . " | SUPPRESSION ANCIENNE OPERATION EFFECTUEE : " . $delOperats . " OPERATIONS \n");

    if ($filtre_import) {
        // L'import est fini, on stocke la date d'import
        $date_dernier_import->valeur_date = date('Y-m-d');
        $date_dernier_import->store();
    }
    fwrite($f_log, date("j M H:i:s") . " | MISE A JOUR DATE PARAMETRE IMPORT FAIT \n");

    fwrite($f_log, date("j M H:i:s") . " | -- FIN -- \n");
    fclose($f_log);
}

$tpl->assign('file_deleted', false);

/**
 * SUPPRESSION DES LOGS 
 */
if (array_key_exists('supprimer', $_POST)) {
    $delete_files = $_POST['delete_files'];
    $nb = 0;
    foreach ($delete_files as $fn) {
        if (unlink('historique_import/' . $fn)) {
            $nb++;
        }
    }
    $tpl->assign('files_deleted', $nb);
    $tpl->assign('file_deleted', true);
}

// Récupération de l'historique import
$fich_list = array();
$n = 0;
if (is_dir('historique_import')) {
    $dh = opendir('./historique_import');
    while (false !== ($filename = readdir($dh))) {
        if (preg_match('/.txt$/i', $filename)) {
            $fich_list[filemtime('historique_import/' . $filename) . '_' . $n++] = $filename;
        }
    }
}

// Tri inverse
krsort($fich_list);

// Calcul de l'historique
$historique = array();
foreach ($fich_list as $fn) {
    $impt = new stdClass();
    $impt->name = $fn;
    $impt->date = date("d M Y à H:i", filemtime('historique_import/' . $fn));
    $impt->size = '' . round(filesize('historique_import/' . $fn) / 1024, 0) . ' ko';
    $impt->type = filetype('historique_import/' . $fn);
    $impt->members = 0;
    $impt->operations = 0;
    $impt->imported = '#N/A';
    $f = fopen('historique_import/' . $fn, 'r');
    $nb = 0;
    $datemaj = '1980-01-01';
    while (($line = fgetcsv($f, 1000, ';', '"')) !== FALSE) {
        $nb++;
        if ($line[0] == 'MEMBRES') {
            $impt->members++;
        }
        if ($line[0] == 'OPERATIONS') {
            $impt->operations++;
        }
        if ($line[0] == 'LIGNE_IMPORT') {
            $impt->imported = $line[1];
        }
        if ($line[0] == 'MEMBRES' || $line[0] == 'OPERATIONS') {
            $dt = dateAccessToString($line[count($line) - 1], true);
            if ($dt && $dt > $datemaj) {
                $datemaj = $dt;
            }
        }
    }
    fclose($f);
    $impt->lines = $nb;
    $impt->datemaj = date('d M Y', strtotime($datemaj));
    $historique[] = $impt;
}

/**
 * Le traitement est terminé, on affiche le template
 */
$tpl->assign('file_imported', $file_imported);
$tpl->assign('table_created', $table_created);
$tpl->assign('choix_annee', $choix_annee);
$tpl->assign('liste_annees', $liste_annees);
$tpl->assign('annee_selectionnee', date('Y'));
$tpl->assign('lignes_lues', $row);
$tpl->assign('lignes_mises_a_jour', $ligne_mise_a_jour);
$tpl->assign('fichier_deja_importe', $fichier_deja_importe);
$tpl->assign('name_uploaded_file', $name_uploaded_file);
$tpl->assign('date_dernier_import', PiloteParametre::getValeurParametre(PiloteParametre::PARAM_DERNIER_IMPORT));
$tpl->assign('nom_section', $preferences->pref_slogan);

$tpl->assign('historique', $historique);

$content = $tpl->fetch('import.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
