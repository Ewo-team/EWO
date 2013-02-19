<?php

namespace conf;
use conf\Helpers as Helpers;

/**
 * Include de toutes les fonctions principale du jeux
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package conf
 */

session_start();

ini_set('display_error', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Paris");

include_once 'config.php';

// Requis pour la connexion a la bdd
include_once(SERVER_ROOT."/conf/connect.conf.php");

// Declaration du controle de connexion
include_once (SERVER_ROOT."/conf/controle_connexion.php");

// Fonctions du site
include_once(SERVER_ROOT."/conf/fonctions.php");

// Magasin de variable
//include_once("VariableStorage.php");

// Helpers
//include_once("Helpers.php");

// Autoloader
include_once('autoloader.php');

if(isset($_SESSION['utilisateur']['id'])) {
	$utilisateur_id = $_SESSION['utilisateur']['id'];
}

?>
