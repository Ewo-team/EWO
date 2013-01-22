<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

//print_r($_POST);

if (!empty($_POST['nom_objet']) AND !empty($_POST['description_objet']) AND !empty($_POST['image']) AND isset($_POST['pv_max']) AND isset($_POST['bloquant'])){
	// Paramètres de connexion à la base de données
	bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description_objet']);
	$nom = mysql_real_escape_string($_POST['nom_objet']);
	
	$sql1="INSERT INTO case_objet_simple (id, nom, description, bloquant, pv_max, poid, image, categorie_id) VALUE (
				'', 
				'".$nom."', 
				'".$description."', 
				'".$_POST['bloquant']."', 				
				'".$_POST['pv_max']."', 
				'".$_POST['poid']."', 
				'".$_POST['image']."', 
				'".$_POST['categorie_id']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:objet.php");
}else{
	echo 'lien null';
}
?>
