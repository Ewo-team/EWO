<?php
/**
 * Compte, Edition grille
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category grille
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['grille']) AND $_POST['grille']=='ok'){
	$grille = 1;
}else{
	$grille = 0;	
}

//echo $grille;exit;

$ewo = bdd_connect("ewo");
$utilisateur_id = $_SESSION['utilisateur']['id'];
mysql_query("UPDATE utilisateurs_option SET grille = '$grille' WHERE utilisateur_id = '$utilisateur_id'")or die(mysql_error());
mysql_close($ewo);

header("location:../compte/options.php");exit;
?>
