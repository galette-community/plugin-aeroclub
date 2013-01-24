<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define('PILOTE_PREFIX', 'pilote_');
require_once 'classes/piloteInstructeur.class.php';

global $login;
$_tpl_array = array(
    'is_instructeur' => PiloteInstructeur::isPiloteInstructeur($login->login)
);
?>
