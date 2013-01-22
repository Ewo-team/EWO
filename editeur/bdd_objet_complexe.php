<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

//print_r($_POST);

if (!empty($_POST['nom_objet']) AND !empty($_POST['description_objet']) AND !empty($_POST['image']) AND isset($_POST['pv_max']) AND isset($_POST['taille_x']) AND isset($_POST['taille_y']) AND isset($_POST['bloquant']) AND isset($_POST['reparable'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$taillex = $_POST['taille_x']-1;
	$tailley = $_POST['taille_y']-1;
	
	$description = mysql_real_escape_string($_POST['description_objet']);
	$nom = mysql_real_escape_string($_POST['nom_objet']);
	
	$sql1="INSERT INTO case_objet_complexe (id, nom, description, pv_max,	bloquant, reparable, images, taille_x, taille_y, categorie_id) VALUE (
				'', 
				'".$nom."', 
				'".$description."', 
				'".$_POST['pv_max']."', 
				'".$_POST['bloquant']."', 
				'".$_POST['reparable']."', 
				'".$_POST['image']."', 
				'".$taillex."', 
				'".$tailley."', 
				'".$_POST['categorie_id']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:objet_complexe.php");
}else{
	echo 'lien null';
}
?>
