<?php
//-- Header --
$root_url = "./../..";

include($root_url."/template/header_new.php");
require_once ("../AdminDAO.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Si la variable  $_SESSION['temp']['erreurs'] est définie, alors stocke sa valeur dans la variable $msg_error .
if(isset($_SESSION['temp']['erreurs'])){
	$msg_error = $_SESSION['temp']['erreurs'];
	unset($_SESSION['temp']['erreurs']);
}
// Sinon le message d'erreur sera vide.
else{
	$msg_error="";
}

if(isset($_REQUEST['id'])) {
	if(is_numeric($_REQUEST['id'])) {
	
		$conn = AdminDAO::getInstance();
		// On supprime
		$reussite = $conn->SupprimerAction($_REQUEST['id']);
		$conn->CleanupEffets();
		if($reussite) {
			$titre = "Action";
			$text = "L'action à bien été effacée.";
			$lien = "../admin/gestion_actions/";
			$root = "../..";
			gestion_erreur($titre, $text, $root, $lien);			
		} else {
			$titre = "Action";
			$text = "Une erreur est survenue.";
			$lien = "../admin/gestion_actions/";
			$root = "../..";
			gestion_erreur($titre, $text, $root, $lien);			
		}

	} else {
		$titre = "Action";
		$text = "L'identifiant spécifié n'est pas un nombre.";
		$lien = "../admin/gestion_actions/";
		$root = "../..";
		gestion_erreur($titre, $text, $root, $lien);	
	}
} else {
	$titre = "Action";
	$text = "L'identifiant n'as pas été spécifié.";
	$lien = "../admin/gestion_actions/";
	$root = "../..";
	gestion_erreur($titre, $text, $root, $lien);	
}
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
