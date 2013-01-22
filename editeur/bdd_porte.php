<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom_porte']) AND !empty($_POST['description_porte']) AND !empty($_POST['image_porte']) AND isset($_POST['posX']) AND isset($_POST['posY']) AND isset($_POST['pv_max']) AND !empty($_POST['spawn_id']) AND !empty($_POST['carte_id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description_porte']);
	$nom = mysql_real_escape_string($_POST['nom_porte']);
	
	$x_min=$_POST['posX']-2;
	$x_max=$_POST['posX']+5;
	$y_min=$_POST['posY']-5;
	$y_max=$_POST['posY']+2;
	
	$sql		="SELECT id FROM case_objet_complexe WHERE images='decors/objets_complexe/D".$_POST['image_porte']."'";
	$resultat 	= mysql_query ($sql) or die (mysql_error());
	$pos		= mysql_fetch_array($resultat);

	$obj_id=$pos['id'];

	$sql1="INSERT INTO damier_objet_complexe 
	(id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) VALUE 
	('', '".$obj_id."', '".$x_min."', '".$x_max."', '".$y_min."', '".$y_max."', '-1', '".$_POST['carte_id']."')";
	mysql_query($sql1) or die (mysql_error());
	//-- Recup de l'id de l'objet complexe inséré
	$id_DB = mysql_insert_id();
	if(!isset($_POST['porte_liee'])){
		$_POST['porte_liee']=0;
		}
		
	$sql1="INSERT INTO damier_porte (id, nom, nom_image, description, pos_x, pos_y,porte_liee_id, objet_lie, spawn_id, carte_id, pv, pv_max, statut) VALUE ('', '".$nom."', '".$_POST['image_porte']."', '".$description."', '".$_POST['posX']."', '".$_POST['posY']."','".$_POST['porte_liee']."', '$id_DB', '".$_POST['spawn_id']."', '".$_POST['carte_id']."', '".$_POST['pv_max']."', '".$_POST['pv_max']."',  '".$_POST['statut']."')";
	mysql_query($sql1) or die (mysql_error());
	$id_DB = mysql_insert_id();
	
	$sql1="UPDATE damier_porte SET porte_liee_id='$id_DB' WHERE id ='".$_POST['porte_liee']."'";
	mysql_query($sql1) or die (mysql_error());
	header("location:porte.php");
}else{
	echo 'lien null';
}
?>
