<?php
/**
 * Gestion du l'utilisateur via l'api
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

if(isset($_REQUEST['id_utilisateur']) && $_REQUEST['action']=='nom' ){
	// Paramètres de connexion à la base de données
	$ewo = bdd_connect('ewo');	
	
	$sql="SELECT id,nom,passwd,droits FROM utilisateurs WHERE id = '".$_REQUEST['id_utilisateur']."'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$connexion = mysql_fetch_array ($resultat);
	
	$utilisateur_nom = array('nom_utilisateur' => $connexion['nom']);
	echo json_encode($utilisateur_nom);
}


?>
