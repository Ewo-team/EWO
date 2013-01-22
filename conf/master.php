<?php
/**
 * Include de toutes les fonctions principale du jeux
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package conf
 */
 
date_default_timezone_set("Europe/Paris");

// Requis pour la connexion a la bdd
include_once($root_url."/conf/connect.conf.php");

// Declaration du controle de connexion
include_once ($root_url."/connexion/controle_connexion.php");

// Fonctions du site
include_once($root_url."/conf/fonctions.php");

// Fonctions du forum
include_once($root_url."/lib_tier/forum/forum.connect.php");

// Magasin de variable
include_once("VariableStorage.php");

?>
