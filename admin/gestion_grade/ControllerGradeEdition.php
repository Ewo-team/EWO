<?php
session_start(); 
$root_url = "./../..";
//-- Header --
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');

// Déclaration de la variable temporaire d'erreur
$_SESSION['temp']['erreurs'] = "";

// Mise sous variables des données récupérées
if(!isset($_GET['suppr_grade']))
	{
		$grade_a_creer = mysql_real_escape_string($_POST['grade_a_creer']);
		$description_du_grade = mysql_real_escape_string($_POST['description_du_grade']);
		$id_camp = mysql_real_escape_string($_POST['id_camp']);
		$color = mysql_real_escape_string($_POST['couleur']);
		$new_grade_id = mysql_real_escape_string($_POST['new_grade_id']);
		$new_race_id = mysql_real_escape_string($_POST['new_race_id']);
	}

// Cas d'une édition de grade
if(isset($_POST['editGrade']))
{
	$id_grade = mysql_real_escape_string($_POST['id_grade']);
	$id_race  = mysql_real_escape_string($_POST['id_race']);
	$requete_edition = mysql_query("UPDATE `races` 
										SET `camp_id` = '$id_camp',
											`nom` = '$grade_a_creer',
											`race_id` = '$new_race_id',
											`description` = '$description_du_grade', 
											`grade_id` = '$new_grade_id',
											`color`= '$color'
										WHERE `races`.`grade_id` = '$id_grade' AND `races`.`race_id` = '$id_race'; 
									");
	
	if($requete_edition == FALSE)
	{
		$msg_error = "Echec de la maj.";
	}
	else
	{
		$msg_error = "maj r&eacute;ussi.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	
	
	echo '<script language="javascript" type="text/javascript" >document.location="gestion_grade.php";</script>';

}

// Cas d'une création de grade
if(isset($_POST['creer_grade']))
{
	// On vérifie que le grade n'est pas déjà créé
	$verif_grade_existe = mysql_query("SELECT nom FROM `races` WHERE nom = '$grade_a_creer'");
	if (mysql_fetch_row($verif_grade_existe))
	{
		$msg_error = "Ce nom de grade semble déjà exister dans la base. Vérifier et/ou recommencer.";
		
		$_SESSION['temp']['erreurs'] = $msg_error;

		echo '<script language="javascript" type="text/javascript" >document.location=="gestion_grade.php"</script>';

	}
	else
	{
		$requete_creation = mysql_query("INSERT INTO `races` ( `id`, `race_id`, `grade_id`, `color`, `camp_id` , `nom` , `description`) 
												VALUES('', '$new_race_id', '$new_grade_id', '$color', '$id_camp', '$grade_a_creer', '$description_du_grade')
										")or die (mysql_error());		
		
		if($requete_creation == FALSE)
		{
			$msg_error = "Echec lors de la cr&eacute;ation";
		}
		else
		{
			$msg_error = "Cr&eacute;ation r&eacute;ussie.";
		}
		$_SESSION['temp']['erreurs'] = $msg_error;
		
		echo '<script language="javascript" type="text/javascript" >document.location="gestion_grade.php"</script>' ;
	}
}

// Cas d'une suppression
if(isset($_GET['suppr_grade']))
{
	$suppr_grade = $_GET['suppr_grade'];
	$suppr_race = $_GET['race_id'];
	
	$requette_suppression = mysql_query("DELETE FROM `races` WHERE `races`.`grade_id` = '$suppr_grade' AND `races`.`race_id` = '$suppr_race'") or die(mysql_error());	
	
	if($requette_suppression == FALSE)
	{
		$msg_error = "Echec lors de la suppression";
	}
	else
	{
		$msg_error = "Suppression r&eacute;ussie.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	
	echo '<script language="javascript" type="text/javascript" >document.location="gestion_grade.php"</script>' ;
	
}
?>
