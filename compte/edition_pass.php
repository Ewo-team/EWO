<?php
/**
 * Compte, Edition password
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category password
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['pass_modif'])){
	$ewo = bdd_connect("ewo");
	$pass = mysql_real_escape_string($_POST['pass_modif']);
	$passencode = hash ('sha256',$pass);
	$utilisateur_id = $_SESSION['utilisateur']['id'];

	mysql_query("UPDATE utilisateurs SET passwd = '$passencode' WHERE id = '$utilisateur_id'");
	mysql_close($ewo);

	header('location:../compte/');exit;
}else{
	$titre = "Vous n'êtes pas autorisés à effectuer cette action.";
	$text = "Cet utilisateur n'existe pas.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
