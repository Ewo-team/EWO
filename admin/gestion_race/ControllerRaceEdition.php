<?php
session_start();
//-- Header --
$root_url = "./../..";
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

// Déclaration de la variable temporaire d'erreur
$_SESSION['temp']['erreurs'] = "";

// Mise sous variables des données récupérées
if (isset($_POST['id_race']))
	$id_race = mysql_real_escape_string($_POST['id_race']);
if (isset($_POST['race_a_creer']))
	$race_a_creer = mysql_real_escape_string($_POST['race_a_creer']);
if (isset($_POST['description_de_la_race']))
	$description_de_la_race = mysql_real_escape_string($_POST['description_de_la_race']);
if (isset($_POST['id_camp']))
	$id_camp = mysql_real_escape_string($_POST['id_camp']);
if (isset($_POST['couleur']))
	$couleur = mysql_real_escape_string($_POST['couleur']);
if (isset($_POST['type']))
	$type = mysql_real_escape_string($_POST['type']);
	else $type = 3;

// Cas d'une édition de race
if(isset($_POST['editRace']))
{
	$requete_edition = mysql_query("UPDATE races SET type = $type, camp_id='$id_camp', nom='$race_a_creer', description='$description_de_la_race', color='$couleur' WHERE race_id= '$id_race' AND grade_id='-2'");
	$requete_edition = mysql_query("UPDATE races SET type = $type, camp_id='$id_camp', description='$description_de_la_race', color='$couleur' WHERE race_id= '$id_race' AND grade_id!='-2'");
	$requete_edition = mysql_query("UPDATE races SET type = $type, camp_id='$id_camp', nom='Tricheur', description='$description_de_la_race', color='#E90086' WHERE race_id= '$id_race' AND grade_id='-1'");
		
	if($requete_edition == FALSE)
	{
		$msg_error = "Echec de la maj.";
	}
	else
	{
		$msg_error = "maj r&eacute;ussi.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	

	echo '<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_race&edit_race='.$id_race.'"</script>';

}

// Cas d'une création de race
if(isset($_POST['creer_race']))
{

	// On vérifie que la race n'est pas déjà créée
	$verif_race_existe = mysql_query("SELECT nom FROM races WHERE nom='$race_a_creer'") or die(mysql_error());
	if (mysql_fetch_row($verif_race_existe))
	{
		$msg_error = "Ce nom de race semble déjà exister dans la base. Vérifier et/ou recommencer.";
		
		$_SESSION['temp']['erreurs'] = $msg_error;

		echo '<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_race"</script>';

	}
	else
	{
		$requete_creation = mysql_query("SELECT MAX(race_id) FROM races") or die(mysql_error());
		$reponse = mysql_fetch_array($requete_creation);
		$id_race=$reponse[0] + 1;
		for ($inc=-2; $inc<=5; $inc++)
			{
				$requete_creation = mysql_query("INSERT INTO races (id, race_id, grade_id, camp_id, nom, description, color, type) VALUES('', '$id_race', '$inc','$id_camp', '$race_a_creer', '$description_de_la_race', '$couleur', '$type')") or die(mysql_error());
				
			}
		$requete_creation = mysql_query("UPDATE races SET camp_id='$id_camp', nom='Tricheur', description='$description_de_la_race', color='#E90086' WHERE race_id= '$id_race' AND grade_id='-1'") or die(mysql_error());
		
		if($requete_creation == FALSE)
		{
			$msg_error = "Echec lors de la cr&eacute;ation";
		}
		else
		{
			$msg_error = "Cr&eacute;ation r&eacute;ussie.";
		}
		$_SESSION['temp']['erreurs'] = $msg_error;
		
		echo '<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_race"</script>';

	}
}

// Cas d'une suppression
if(isset($_GET['suppr_race']))
{
	$suppr_race = mysql_real_escape_string($_GET['suppr_race']);
	
	$requette_suppression = mysql_query("DELETE FROM `races` WHERE `races`.`race_id`='$suppr_race'") or die(mysql_error());
	
	if($requette_suppression == FALSE)
	{
		$msg_error = "Echec lors de la suppression";
	}
	else
	{
		$msg_error = "Suppression r&eacute;ussie.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	
	echo '<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_race"</script>';

	
}
 ?>
