<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom_carte']) AND !empty($_POST['description_carte']) AND isset($_POST['circ']) AND isset($_POST['infini']) AND isset($_POST['x_min']) AND isset($_POST['y_min']) AND isset($_POST['x_max']) AND isset($_POST['y_max'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description_carte']);
	$nom = mysql_real_escape_string($_POST['nom_carte']);
	
	$sql1="INSERT INTO cartes (id, nom, description, circ, infini, x_min,	y_min, x_max, y_max, visible_x_min, visible_x_max, visible_y_min, visible_y_max) 
	VALUE ('', 
				'".$nom."', 
				'".$description."', 
				'".$_POST['circ']."', 
				'".$_POST['infini']."', 				
				'".$_POST['x_min']."', 				
				'".$_POST['y_min']."', 				
				'".$_POST['x_max']."', 				
				'".$_POST['y_max']."', 				
				'".$_POST['visible_x_min']."', 				
				'".$_POST['visible_x_max']."', 				
				'".$_POST['visible_y_min']."', 
				'".$_POST['visible_y_max']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:carte.php");
}else{
	echo 'lien null';
}
?>
