<?php
session_start(); 
$root_url = "./../..";
//-- Header --
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

//-- Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');


// Déclaration de la variable temporaire d'erreur
$_SESSION['temp']['erreurs'] = "";

// Mise sous variables des données récupérées
if(!isset($_POST['creer_camp'])){
	$id_camp = mysql_real_escape_string($_POST['id_camp']);
	}
$camp_a_creer = mysql_real_escape_string($_POST['camp_a_creer']);
$description_du_camp = mysql_real_escape_string($_POST['description_du_camp']);
$id_carte = mysql_real_escape_string($_POST['id_carte']);


// Cas d'une édition de camp
if(isset($_POST['editCamp']))
{
	$requete_edition = mysql_query("UPDATE `camps` SET `carte_id` = '$id_carte',`nom` = '$camp_a_creer',`description` = '$description_du_camp'  WHERE `camps`.`id` = '$id_camp'; ");
	
	if($requete_edition == FALSE)
	{
		$msg_error = "Echec de la maj.";
	}
	else
	{
		$msg_error = "maj r&eacute;ussi.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	
	?>
	<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_camp&edit_camp=<?php echo $id_camp; ?>";</script>
	<?php
}

// Cas d'une création de camp
if(isset($_POST['creer_camp']))
{
	// On vérifie que le camp n'est pas déjà créé
	$verif_camp_existe = mysql_query("SELECT nom FROM `camps` WHERE nom = '$camp_a_creer'");
	if (mysql_fetch_row($verif_camp_existe))
	{
		$msg_error = "Ce nom de camp semble déjà exister dans la base. Vérifiez et/ou recommencez.";
		
		$_SESSION['temp']['erreurs'] = $msg_error;
		?>
		<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_camp"</script>
		<?php
	}
	else
	{
		$requete_creation = mysql_query("INSERT INTO `camps` ( `id` , `carte_id` , `nom` , `description` ) VALUES('','$id_carte','$camp_a_creer','$description_du_camp')");
		
		if($requete_creation == FALSE)
		{
			$msg_error = "Echec lors de la cr&eacute;ation";
		}
		else
		{
			$msg_error = "Cr&eacute;ation r&eacute;ussie.";
		}
		$_SESSION['temp']['erreurs'] = $msg_error;
		
		?>
		<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_camp"</script>
		<?php
	}
}

// Cas d'une suppression
if(isset($_GET['suppr_camp']))
{
	$suppr_camp = $_GET['suppr_camp'];
	
	$requette_suppression = mysql_query("DELETE FROM `camps` WHERE `camps`.`id` = $suppr_camp;");
	
	if($requette_suppression == FALSE)
	{
		$msg_error = "Echec lors de la suppression";
	}
	else
	{
		$msg_error = "Suppression r&eacute;ussie.";
	}
	$_SESSION['temp']['erreurs'] = $msg_error;
	
	?>
	<script language="javascript" type="text/javascript" >document.location="index.php?page=gestion_camp"</script>
	<?php
	
}
