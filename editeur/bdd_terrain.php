<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom_objet']) AND !empty($_POST['image']) AND isset($_POST['mouv']) AND isset($_POST['couleur'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$nom = mysql_real_escape_string($_POST['nom_objet']);
	
	$sql1="INSERT INTO case_terrain (id, nom, image, couleur, mouv, categorie_id) VALUE (
				'', 
				'".$nom."', 
				'".$_POST['image']."', 
				'".$_POST['couleur']."', 				
				'".$_POST['mouv']."', 
				'".$_POST['categorie_id']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:objet_decor.php");
}else{
	echo 'lien null';
}
?>
