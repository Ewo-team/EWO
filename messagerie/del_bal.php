<?php
/**
 * Supprime une bal
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
include ("messagerieDAO.php");
/*-- Connexion requise --*/
if (ControleAcces('utilisateur',0) == false){
	echo "acces denied";exit;
}
/*-----------------------*/

if (isset($_GET['id']) AND isset($_GET['exp'])){
	if ($_SESSION['perso']['id'] == $_GET['exp']){

	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();
	
	$id = $_GET['id'];

	if(isset($_GET['page']) && $_GET['page']=='send'){
		$flag = $conn->DelBalSend($id);
	}else{
		$flag = $conn->DelBal($id);
	}	
	echo 'true';
	
	}else{
		echo 'null';	
	}
}else{
	echo 'null';
}
?>
