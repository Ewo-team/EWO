<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (!empty($_POST['nom_objet']) AND !empty($_POST['description_objet']) AND !empty($_POST['image']) AND isset($_POST['pv_max']) AND isset($_POST['rarete']) AND isset($_POST['cout']) AND isset($_POST['poid']) && ($_POST['consom'] <= 4 AND $_POST['consom'] >= 0)){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$description = mysql_real_escape_string($_POST['description_objet']);
	$nom = mysql_real_escape_string($_POST['nom_objet']);
	
	$sql1="INSERT INTO case_artefact (id, nom, description, image, pv_max, rarete, cout, poid, categorie_id, consom) VALUE (
				'', 
				'".$nom."', 
				'".$description."', 
				'".$_POST['image']."', 
				'".$_POST['pv_max']."', 				
				'".$_POST['rarete']."', 
				'".$_POST['cout']."', 
				'".$_POST['poid']."', 
				'".$_POST['categorie_id']."',
				'".$_POST['consom']."')";
	mysql_query($sql1) or die (mysql_error());
	$id_artefact = mysql_insert_id();
	$sql3="INSERT INTO caracs_alter_artefact (case_artefact_id, alter_pa, alter_mouv,	alter_def, alter_att, alter_recup_pv, alter_force, alter_perception) VALUE ('".$id_artefact."',0,0,0,0,0,0,0)";
	mysql_query($sql3) or die(mysql_error());
	header("location:objet_artefact.php");
}else{
	echo 'lien null';
}
?>
