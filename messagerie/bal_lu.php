<?php

namespace messagerie;

/**
 * Change le statuts d'une bal en lu ou non
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
require_once __DIR__ . '/../conf/master.php';

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
	$exp = $_GET['exp'];

	$flag = $conn->UpdateLu($id, $exp);
	echo 'true';
	
	}else{
		echo 'null';
	}
}else{
	echo 'null';
}
?>
