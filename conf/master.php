<?php
/**
 * Include de toutes les fonctions principale du jeux
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package conf
 */


date_default_timezone_set("Europe/Paris");

include_once 'constant.php';

// Requis pour la connexion a la bdd
include_once(SERVER_ROOT."/conf/connect.conf.php");

// Declaration du controle de connexion
include_once (SERVER_ROOT."/connexion/controle_connexion.php");

// Fonctions du site
include_once(SERVER_ROOT."/conf/fonctions.php");

// Fonctions du forum
include_once(SERVER_ROOT."/lib_tier/forum/forum.connect.php");

// Magasin de variable
include_once("VariableStorage.php");

// Helpers
include_once("Helpers.php");

// Autoloader
include_once('autoloader.php');

?>
