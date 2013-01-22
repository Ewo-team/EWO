<?php
/**
 * Compte, Edition rose
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category rose
 */
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['rose']) AND $_POST['rose']=='1'){
	$rose = 1;
}else{
	$rose = 0;	
}


$ewo = bdd_connect("ewo");
$utilisateur_id = $_SESSION['utilisateur']['id'];
mysql_query("UPDATE utilisateurs_option SET rose = '$rose' WHERE utilisateur_id = '$utilisateur_id'")or die(mysql_error());
mysql_close($ewo);

header("location:../compte/options.php");exit;
?>
