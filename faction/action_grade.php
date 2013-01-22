<?php
/**
 * Faction - 
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package faction
 */
 
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('utilisateur',1);
/*-----------------------*/

include("./controle_membre.php");
include("./fonctions.php");

// Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

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


if (isset($_GET['action'])){
$action = mysql_real_escape_string($_GET['action']);
}

$utilisateur_id = $_SESSION['utilisateur']['id'];
$uperso_id = $_SESSION['utilisateur']['perso_id'];


if($action=='del')
{
$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
$virer_bras = Controle_membre('1',$uperso_id, $faction_id);
$gestion_grade = Controle_membre('3',$uperso_id, $faction_id);

$droits = $virer_chef.$virer_bras.$gestion_grade;
$droits = b"$droits";

if($droits>=b'001'  || ControleAcces('anim',0) || ControleAcces('admin',0))
	{
		if ($grade_id!="4")
		{
			//recuperation des droits du grades à modifier
			$sql ="SELECT * FROM faction_grades WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id'";
			$reponse = mysql_query($sql) or die (mysql_error());
			$reponse = mysql_fetch_array($reponse);
			
			$droits_grade = $reponse['droits'][0].$reponse['droits'][1].'1';
			$droits_grade = b"$droits_grade";
			
			if($droits_grade <= $droits){
				del_grade($faction_id, $grade_id);
			}else{
				echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
			}
		}else{
			echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
		}
	}else{
		echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
			}
echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
}

if(isset($_POST['modif_grade']))
{
	//recuperation des droits du membre
	$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
	$virer_bras = Controle_membre('1',$uperso_id, $faction_id);
	$gestion_grade = Controle_membre('3',$uperso_id, $faction_id);
	$droits = $virer_chef.$virer_bras.$gestion_grade;
	$droits = b"$droits";
	$auto = $virer_chef.$virer_bras;
	$auto = b"$auto"; 
	
	//recuperation des droits du grades à modifier
	$sql ="SELECT * FROM faction_grades WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id'";
	$reponse = mysql_query($sql) or die (mysql_error());
	$reponse = mysql_fetch_array($reponse);
	
	$droits_grade = $reponse['droits'][0].$reponse['droits'][1];
	$droits_grade = b"$droits_grade";
	if($droits>=b'001'  || ControleAcces('anim',0) || ControleAcces('admin',0))
	{
		$nom = mysql_real_escape_string($_POST['nom_grade']);
		if(isset($_POST['droit0'])){
			$droits = $_POST['droit0'];
		} else $droits="0";
		for($inc=1; $inc<=7; $inc++)
		{
			if(isset($_POST['droit'.$inc])){
				$droits = $droits.$_POST['droit'.$inc];
				} else $droits=$droits."0";
		$droits_ = b"$droits";
			if ($inc==1 && ($droits_ > $auto || $droits_grade > $auto) && !ControleAcces('anim',0) && !ControleAcces('admin',0))
			 {
			  echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";
			  exit;
			 }
		}
	$sql = "UPDATE `ewo`.`faction_grades` SET `droits` = '$droits_', `nom`='$nom' WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id' LIMIT 1 ;";
	mysql_query($sql) or die (mysql_error());

	}
	else {
			echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
			}
	echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;

}

if(isset($_POST['creer_grade']))
{
	$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
	$gestion_grade = Controle_membre('3',$uperso_id, $faction_id);
	$droits = $virer_chef.$gestion_grade;
	$droits = b"$droits";
	$nom = mysql_real_escape_string($_POST['nom_grade']);

	if(($droits>=b'01'  || ControleAcces('anim',0) || ControleAcces('admin',0)) && $nom!=''){
			
			if (isset($_POST['droit0']))
				{
				$droits = mysql_real_escape_string($_POST['droit0']);
				}
				else $droits = '0';

			for($inc=1; $inc<=7; $inc++)
			{
			if (isset($_POST['droit'.$inc]))
				{
				$droits = $droits.mysql_real_escape_string($_POST['droit'.$inc]);
				}
				else $droits = $droits.'0';
			}

			$requete_creation = mysql_query("SELECT MAX(grade_id) FROM faction_grades WHERE faction_id='$faction_id'") or die (mysql_error());
			$reponse = mysql_fetch_array($requete_creation);
			if(isset($reponse))
			{
			$id_grade=$reponse[0] + 1;
			}
			else $id_grade = 1;

			$sql_faction = mysql_query("INSERT INTO faction_grades(id,
														grade_id,
														faction_id,
														nom,
														description,
														droits) 
											VALUES ('',
													'$id_grade', 
													'$faction_id', 
													'$nom', 
													'', 
													'$droits')
									") or die (mysql_error());
			}
			else {
				echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
				}
		
	echo "<script language='javascript' type='text/javascript' >document.location='../faction/editer_faction.php?id=".$faction_id."&perso_id=".$uperso_id."'</script>";exit;
}

if($action=='bal')
{
$virer_chef = Controle_membre('0',$uperso_id, $faction_id);
$bal = Controle_membre('6',$uperso_id, $faction_id);
$droits = $virer_chef.$bal;
$droits = b"$droits";

if ($droits>=b'01'  || ControleAcces('anim',0) || ControleAcces('admin',0))
 {
 if ($grade_id!=0)
 {
  bal_grade($faction_id, $grade_id, $uperso_id);
 }
 else {
	bal_faction($faction_id, $uperso_id);
	}
 }
 else {
		echo "<script language='javascript' type='text/javascript' >document.location='../faction/impossible.php'</script>";exit;
		}
}

?>
