<?php
/**
 * Gestion de la bal grace a l'api
 *
 * 
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 0.1
 * @package api
 */
session_start();
$root_url = "..";
//-- Si connexion obligatoir pour la session
include ($root_url."/conf/master.php");
include ("api.conf.php");
include ($root_url."/messagerie/messagerieDAO.php");

if(isset($_REQUEST['id_perso']) && $_REQUEST['action']=='bal_lu' ){
	$id_perso = $_REQUEST['id_perso'];
	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();
	$retour = $conn->SelectBalRecu($id_perso);
	
	echo json_encode($retour);
}else{
	echo json_encode(array('statut'=>'refuse'));
}
?>
