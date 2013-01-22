<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom'])){
	// Paramètres de connexion à la base de données
	bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description']);
	$nom = mysql_real_escape_string($_POST['nom']);
	$img = 'bouclier_'.$_POST['type_id'];
	$img_dec = 'DBouclier_'.$_POST['type_id'];
	switch($_POST['type_id']){
		case 1 :
			$x_min=$_POST['posX']-5;
			$x_max=$_POST['posX']+5;
			$y_min=$_POST['posY']-5;
			$y_max=$_POST['posY']+5;
			break;
		case 2 :
			$x_min=$_POST['posX']-9;
			$x_max=$_POST['posX']+10;
			$y_min=$_POST['posY']-10;
			$y_max=$_POST['posY']+9;
			break;
		case 3 :
			$x_min=$_POST['posX']-14;
			$x_max=$_POST['posX']+16;
			$y_min=$_POST['posY']-16;
			$y_max=$_POST['posY']+14;
			break;
		case 4 :
			$x_min=$_POST['posX']-18;
			$x_max=$_POST['posX']+21;
			$y_min=$_POST['posY']-21;
			$y_max=$_POST['posY']+18;
			break;
		default :
			$x_min=$_POST['posX']-6;
			$x_max=$_POST['posX']+5;
			$y_min=$_POST['posY']-6;
			$y_max=$_POST['posY']+5;
			break;
			}
	$sql		="SELECT id FROM case_objet_complexe WHERE images='decors/objets_complexe/DBouclier_".$_POST['type_id']."'";
	$resultat 	= mysql_query ($sql) or die (mysql_error());
	$pos		= mysql_fetch_array($resultat);

	$obj_id=$pos['id'];
	
	$sql1="INSERT INTO damier_objet_complexe 
	(id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) VALUE 
	('', '".$obj_id."', '".$x_min."', '".$x_max."', '".$y_min."', '".$y_max."', '-1', '".$_POST['carte_id']."')";
	mysql_query($sql1) or die (mysql_error());
	//-- Recup de l'id de l'objet complexe inséré
	$id_DB = mysql_insert_id();
	$sql1="INSERT INTO damier_bouclier (id, nom, nom_image, description, pos_x, pos_y, type_id, objet_lie, carte_id, pv, pv_max, deplacer, statut) VALUE ('', '".$nom."', '".$img."', '".$description."', '".$_POST['posX']."', '".$_POST['posY']."', '".$_POST['type_id']."',  '".$id_DB."', '".$_POST['carte_id']."', '".$_POST['pv_max']."', '".$_POST['pv_max']."', '".$_POST['deplacer']."', '".$_POST['statut']."')";
	mysql_query($sql1) or die (mysql_error());
	
	header("location:bouclier.php");
}else{
	echo 'lien null';
}
?>
