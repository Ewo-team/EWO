<?php
/**
 * Compte, Edition mail
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category mail
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['email'])){
$ewo = bdd_connect("ewo");

$email = mysql_real_escape_string($_POST['email']);
$utilisateur_id = $_SESSION['utilisateur']['id'];
$utilisateur_name = $_SESSION['utilisateur']['nom'];
   
if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email))
{
	mysql_query("UPDATE utilisateurs SET email = '$email' WHERE id = '$utilisateur_id'");
	
	$name = $_SESSION['utilisateur']['nom'];
	
	mysql_close($ewo);
	$ewo_forum = bdd_connect("forum");
		
	//-- PHPBB integration, changement de l'adress mail
	if(!$sql_users = mysql_query("UPDATE phpbb_users SET user_email = '$email' WHERE username = '$utilisateur_name'")){
		echo "erreur d'update dans le forum";
	}
	mysql_close($ewo_forum);
	
	header('location:../compte/');exit;
}else{
	$titre = "Modification de compte.";
	$text = "Email non valide.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

}else{
	$titre = "Modification de compte.";
	$text = "Vous n'êtes pas autorisés à effectuer cette action.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
