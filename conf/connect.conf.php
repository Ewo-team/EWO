<?php
/**
 * Configuration
 *
 *	Configuration des informations de base de donnée
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package conf
 */

if(!defined("CONNECT.CONF")) {
define("CONNECT.CONF", true);
	
		
// url ou est le site
$_URL = $_SERVER['HTTP_HOST'];

// Gestion des ticket d'inscription
// 0 : Non ; 1 : Oui
$_TICKET = 0;

// Login SSL
// 0 : Non ; 1 : Oui
$_SSL = 0;


/**
 * Connecteur de base de donnée
 */
function bdd_connect($bdd){

	$conf = info_connect($bdd);
	$connect = mysql_connect($conf['serveur'],$conf['user'],$conf['pass']);
	mysql_select_db($conf['bdd'], $connect);
	mysql_set_charset('utf8');
	return $connect;
}

/**
 * Info de connexion pour les bases de données
 *
 * Rajouter autant de configuration que voulue ^^
 *
 */
function info_connect($i){
	if ($i == "ewo"){
		$conf['serveur'] = "localhost";
		$conf['user'] = "root";
		$conf['pass'] = "";
		$conf['bdd'] = "ewo";
	}elseif($i == "forum"){
		$conf['serveur'] = "localhost";
		$conf['user'] = "root";
		$conf['pass'] = "";
		$conf['bdd'] = "ewo_forum";
	}elseif($i == "blog"){
		$conf['serveur'] = "localhost";
		$conf['user'] = "root";
		$conf['pass'] = "";
		$conf['bdd'] = "ewo_blog";
	}elseif($i == "forum_vf"){
		$conf['serveur'] = "localhost";
		$conf['user'] = "root";
		$conf['pass'] = "";
		$conf['bdd'] = "ewo_forum2";
	}
	return $conf;
  }
}
/*
$XMPP_SERVER = 'jabber.linux-experience.fr';
$XMPP_PORT = 5222;
$XMPP_NOM = 'ewo';
$XMPP_PASS = 'maman24';
*/
?>
