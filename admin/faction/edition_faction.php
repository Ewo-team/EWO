<?php
session_start();
//-- Header --
$root_url = "./../..";
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
bdd_connect('ewo');

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
	 echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

if (isset($_POST['url']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $url = mysql_real_escape_string($_POST['url_faction']);
	 mysql_query("UPDATE `ewo`.`factions` SET `site_url` = '$url' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

if (isset($_POST['nom']))
{
if (ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $nom = mysql_real_escape_string($_POST['nom_faction']);
	 mysql_query("UPDATE `ewo`.`factions` SET `nom` = '$nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

if (isset($_POST['mod_type']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $type = mysql_real_escape_string($_POST['type']);
	 if ($type==0)
	 {
	 $type_nom= 'Faction basique';
	 }
	 elseif ($type==1)
	 {
	 $type_nom= 'Faction de Justice';
	 }
	 elseif ($type==2)
	 {
	 $type_nom= 'Faction de D&eacute;fense';
	 }
	 elseif ($type==3)
	 {
	 $type_nom= 'Faction de Traitre';
	 race_faction($faction_id, 2); //Une faction qui passe tra&icirc;tre est automatiquement mauve
	 }
	 elseif ($type==4)
	 {
	 $type_nom= 'Faction Loyaliste';
	 }

	 mysql_query("UPDATE `ewo`.`factions` SET `type` = '$type', `type_nom`='$type_nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	 echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

if (isset($_POST['supprimer']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 del_faction($faction_id);
	 echo "<script language='javascript' type='text/javascript' >document.location='./liste_faction.php'</script>";exit;
	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

if (isset($_POST['mod_race']))
{
if (Controle_membre('0',$uperso_id, $faction_id)|| ControleAcces('anim',0) || ControleAcces('admin',0))
	{
	 $race = $_POST['race'];
	 race_faction($faction_id, $race); 
	 
	 echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
	}
	else {
		echo "<script language='javascript' type='text/javascript' >document.location='./impossible.php'</script>";exit;
		}
}

?>
