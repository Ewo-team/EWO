<?php
/**
 * Gestion du perso via l'api
 *
 * 
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 0.1
 * @package api
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

$api = connect_api();

if ($api['valide'] == true){

	echo "ok";

}else{
	echo "erreur";
}

?>
