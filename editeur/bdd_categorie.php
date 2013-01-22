<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom']) AND !empty($_POST['description']) AND !empty($_POST['statut'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description']);
	$nom = mysql_real_escape_string($_POST['nom']);
	$statut = mysql_real_escape_string($_POST['statut']);
	
	$sql1="INSERT INTO ".$statut." (id, nom, description) VALUE ('', '".$nom."', '".$description."')";
	mysql_query($sql1);// or die (mysql_error());
	header("location:categorie.php");
}else{
	echo 'lien null';
}
?>
