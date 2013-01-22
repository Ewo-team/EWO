<?php
/**
 * Gestion des Statistique via l'api
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

bdd_connect('ewo');	

// API retournant les statisques
$stat = array(
					"ange_inscrit"   => statistique_perso_inscrit(3),
					"demon_inscrit" => statistique_perso_inscrit(4),
					"humain_inscrit" => statistique_perso_inscrit(1),
					"ange_vivant"   => statistique_persos_vivant(3),
					"demon_vivant" => statistique_persos_vivant(4),
					"humain_vivant" => statistique_persos_vivant(1)					
					);

 /*
 ini_set('display_errors', 0);
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
      header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
      header("Cache-Control: no-cache, must-revalidate" );
      header("Pragma: no-cache" );
      header("Content-type: application/json");
*/
	echo json_encode($stat );
?>
