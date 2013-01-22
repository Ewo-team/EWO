<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['objet_id']) AND isset($_POST['pos_x']) AND isset($_POST['pos_y']) AND isset($_POST['carte_id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$plans = "SELECT nom, taille_x, taille_y, pv_max FROM case_objet_complexe WHERE id=".$_POST['objet_id']."";
	$resultat = mysql_query ($plans) or die (mysql_error());
	$plan = mysql_fetch_array ($resultat);
	
	$pos_x_max = $plan['taille_x']+$_POST['pos_x'];
	$pos_y_max = $plan['taille_y']+$_POST['pos_y'];
	$pv = $plan['pv_max'];
	
	$sql1="INSERT INTO damier_objet_complexe (id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) VALUE (
				'', 
				'".$_POST['objet_id']."', 
				'".$_POST['pos_x']."', 
				'".$pos_x_max."', 				
				'".$_POST['pos_y']."', 
				'".$pos_y_max."', 
				'".$pv."', 
				'".$_POST['carte_id']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:poser_objet_complexe.php");
}else{
	echo 'lien null';
}
?>
