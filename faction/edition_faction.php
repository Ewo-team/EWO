<?php
/**
 * Légion - script d'edition d'une Légion
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package Légion
 */

session_start(); 
$root_url = "..";
include ("../conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

$ewo = bdd_connect('ewo');

if (isset($_POST['faction_id'])){
$faction_id = mysql_real_escape_string($_POST['faction_id']);
}

include("../faction/controle_membre.php");
include("../faction/fonctions.php");
$uperso_id = $_SESSION['utilisateur']['perso_id'];
$faction_id = mysql_real_escape_string($_POST['faction_id']);

if (isset($_POST['description']))
{
	if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $description = mysql_real_escape_string($_POST['description_faction']);
	 mysql_query("UPDATE `ewo`.`factions` SET `description` = '$description' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

if (isset($_POST['url']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $url = mysql_real_escape_string($_POST['url_faction']);
	 mysql_query("UPDATE `ewo`.`factions` SET `site_url` = '$url' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

if (isset($_POST['nom']))
{
if (ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $nom = mysql_real_escape_string($_POST['nom_faction']);
	 mysql_query("UPDATE `ewo`.`factions` SET `nom` = '$nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

if (isset($_POST['mod_type']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $type = mysql_real_escape_string($_POST['type']);
	 if ($type==0)
	 {
	 $type_nom= 'Légion basique';
	 }
	 elseif ($type==1)
	 {
	 $type_nom= 'Légion de Justice';
	 }
	 elseif ($type==2)
	 {
	 $type_nom= 'Légion de D&eacute;fense';
	 }
	 elseif ($type==3)
	 {
	 $type_nom= 'Légion de Traitre';
	 race_faction($faction_id, 2); //Une Légion qui passe traitre est automatiquement mauve
	 }
	 elseif ($type==4)
	 {
	 $type_nom= 'Légion Loyaliste';
	 }

	 mysql_query("UPDATE `ewo`.`factions` SET `type` = '$type', `type_nom`='$type_nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

if (isset($_POST['supprimer']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 del_faction($faction_id); 
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	
	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

if (isset($_POST['mod_race']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $race = $_POST['race'];
	 race_faction($faction_id, $race); 
	 
	 echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
}

$ewo = bdd_connect('ewo');
?>
