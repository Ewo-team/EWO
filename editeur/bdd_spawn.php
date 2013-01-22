<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom_spawn']) AND !empty($_POST['description_spawn']) AND isset($_POST['posX']) AND isset($_POST['posY']) AND !empty($_POST['carte_id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description_spawn']);
	$nom = mysql_real_escape_string($_POST['nom_spawn']);
	
	$sql1="INSERT INTO damier_spawn (id, nom, description, pos_x, pos_y, pos_max_x, pos_max_y, carte_id) VALUE ('', '".$nom."', '".$description."', '".$_POST['posX']."', '".$_POST['posY']."', '".$_POST['posmaxX']."', '".$_POST['posmaxY']."', '".$_POST['carte_id']."')";
	mysql_query($sql1) or die (mysql_error());
	header("location:spawn.php");
}else{
	echo 'lien null';
}
?>
