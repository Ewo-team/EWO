<?php
/**
 * Compte, Edition template
 *
 * Permet a l'utilisateur de changer ca template
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category template
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (!empty($_POST['template'])){
	$ewo = bdd_connect("ewo");
	$template = mysql_real_escape_string($_POST['template']);
	//echo '/templates/themes/'.$template;exit;
	if(is_dir('../template/themes/'.$template)){
		$utilisateur_id = $_SESSION['utilisateur']['id'];
		mysql_query("UPDATE utilisateurs_option SET template = '$template' WHERE utilisateur_id = '$utilisateur_id'") or die (mysql_error());
		mysql_close($ewo);
		header("location:../compte/options.php");exit;
	}else{
		$titre = "Modification de compte";
		$text = "Ceci n'est pas un dossier.";
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
