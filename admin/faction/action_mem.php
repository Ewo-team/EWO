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

include("../faction/controle_membre.php");
include("../faction/fonctions.php");

$action='';

if (isset($_GET['faction_id'])){
$faction_id = mysql_real_escape_string($_GET['faction_id']);
}

if (isset($_POST['faction_id'])){
$faction_id = mysql_real_escape_string($_POST['faction_id']);
}

if (isset($_GET['grade_id'])){
$grade_id = mysql_real_escape_string($_GET['grade_id']);
}

if (isset($_POST['grade_id'])){
$grade_id = mysql_real_escape_string($_POST['grade_id']);
}

if (isset($_GET['perso_id'])){
$perso_id = mysql_real_escape_string($_GET['perso_id']);
}

if (isset($_POST['perso_id'])){
$perso_id = mysql_real_escape_string($_POST['perso_id']);
}

if (isset($_GET['action'])){
$action = mysql_real_escape_string($_GET['action']);
}

$utilisateur_id = $_SESSION['utilisateur']['id'];
$uperso_id = $_SESSION['utilisateur']['perso_id'];

//-----------------------

if($action=='del')
{
$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
$virer_bras = Controle_membre('1',$uperso_id, $faction_id);
$virer_mem = Controle_membre('2',$uperso_id, $faction_id);
$droits = $virer_chef.$virer_bras.$virer_mem;

$virer_chef = Controle_membre('0',$perso_id, $faction_id);
$virer_bras = Controle_membre('1',$perso_id, $faction_id);
$virer_mem  = Controle_membre('2',$perso_id, $faction_id);
$droits_del = $virer_chef.$virer_bras.$virer_mem;

if($droits>=$droits_del  || ControleAcces('anim',0) || ControleAcces('admin',0))
{
del_membre($faction_id, $perso_id);
}
echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
}

//-----------------------

if(isset($_POST['upgrade']))
{
$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
$virer_bras = Controle_membre('1',$uperso_id, $faction_id);
$gestion_grade = Controle_membre('3',$uperso_id, $faction_id);
$droits = $virer_chef.$gestion_grade;
$udroits   = $virer_chef.$virer_bras; //droits utilisateur

$requete = "SELECT droits FROM faction_grades WHERE grade_id='$grade_id' AND faction_id='$faction_id'";
$reponse = mysql_query ($requete) or die (mysql_error());
$droits_ = mysql_fetch_array ($reponse);
$droits_ = $droits_['droits'];
$gdroits = $droits_[0].$droits_[1]; //droits associés au grade

//L'utilisateur doit avoir le droit de modifier les grades, et doit avoir un grade suffisant pour modifier celui-ci
if(($droits>="01" && $udroits>=$gdroits)  || ControleAcces('anim',0) || ControleAcces('admin',0)) 
{
upgrade_mem($perso_id, $grade_id);
}
echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

}

//-----------------------

if (isset($_POST['ask_mem']))
{
$requete = "SELECT utilisateur_id, id, faction_id FROM persos WHERE id='$perso_id' OR nom='$perso_id'";
$reponse = mysql_query ($requete) or die (mysql_error());
$reponse= mysql_fetch_array($reponse);
$user_id = $reponse['utilisateur_id'];
$perso_id = $reponse['id'];

$requete = "SELECT faction_id FROM wait_faction WHERE perso_id='$perso_id' AND faction_id='$faction_id'";
$reponse_wait = mysql_query ($requete) or die (mysql_error());
$reponse_wait= mysql_fetch_array($reponse_wait);

if(isset($reponse['faction_id']) and $reponse['faction_id']==0 and $reponse_wait['faction_id']!=$faction_id){
$sql_faction = mysql_query("INSERT INTO `ewo`.`wait_faction` (`id` ,
															`utilisateur_id` ,
															`perso_id` ,
															`faction_id` ,
															`demandeur`
																)
															VALUES (
																	NULL , '$user_id', '$perso_id', '$faction_id', ''
																	);") or die (mysql_error());
 }

echo "<script language='javascript' type='text/javascript' >document.location='./editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

}

?>
