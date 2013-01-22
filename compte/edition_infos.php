<?php
/**
 * Compte, Edition info utilisateur
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category utilisateur
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['jabberid']) AND isset($_POST['telephone'])){
	$ewo = bdd_connect("ewo");
	$jabberid = mysql_real_escape_string($_POST['jabberid']);
	$telephone = mysql_real_escape_string($_POST['telephone']);
	$utilisateur_id = $_SESSION['utilisateur']['id'];
	mysql_query("UPDATE utilisateurs SET jabberid = '$jabberid', telephone = '$telephone' WHERE id = '$utilisateur_id'");
	mysql_close($ewo);
	header('location:../compte/');exit;
}else{
	$titre = "Modification de compte.";
	$text = "Vous n'êtes pas autorisés à effectuer cette action.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

?>
