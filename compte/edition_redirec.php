<?php
/**
 * Compte, Edition redirect
 *
 * Permet a l'utilisateur de choisir le lien de destination aprés s'etre connecté sur ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (!empty($_POST['redirection'])){
	$ewo = bdd_connect("ewo");
	$redirection = mysql_real_escape_string($_POST['redirection']);
	if($redirection==1 OR $redirection==2 OR $redirection==3){
		$utilisateur_id = $_SESSION['utilisateur']['id'];
		mysql_query("UPDATE utilisateurs_option SET redirection = '$redirection' WHERE utilisateur_id = '$utilisateur_id'") or die (mysql_error());
		mysql_close($ewo);
		header("location:../compte/options.php");exit;
	}else{
		$titre = "Modification de compte";
		$text = "Aucune page ne correspond a votre demande.";
		$lien = "../compte/options.php";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);
	}
}else{
	$titre = "Modification de compte";
	$text = "Vous n'êtes pas autorisés à effectuer cette action.";
	$lien = "../compte/options.php";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

?>
