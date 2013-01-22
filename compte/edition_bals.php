<?php
/**
 * Compte, Edition bal
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category bal
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (!empty($_POST['time'])){
	$ewo = bdd_connect("ewo");
	$time = mysql_real_escape_string($_POST['time']);
	$utilisateur_id = $_SESSION['utilisateur']['id'];
	mysql_query("UPDATE utilisateurs_option SET bals_speed = '$time' WHERE utilisateur_id = '$utilisateur_id'") or die (mysql_error());
	mysql_close($ewo);
	header("location:../compte/options.php");exit;
}else{
	$titre = "Modification de compte";
	$text = "Vous n'êtes pas autorisés à effectuer cette action.";
	$lien = "../compte/options.php";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

?>
