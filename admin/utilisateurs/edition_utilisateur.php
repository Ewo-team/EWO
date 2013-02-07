<?php
require_once __DIR__ . '/../../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

//-- Paramètres de connexion à la base de données
bdd_connect('ewo');

$id_utilisateur = mysql_real_escape_string($_POST['id_utilisateur']);

$nom = mysql_real_escape_string($_POST['nom']);
$mail = mysql_real_escape_string($_POST['mail']);
$jabberid = mysql_real_escape_string($_POST['jabberid']);

if (isset($_POST['droit1'])&&$_POST['droit1']==1)
	$droit1 = mysql_real_escape_string($_POST['droit1']);
else $droit1=0;

if (isset($_POST['droit2'])&&$_POST['droit2']==1)
	$droit2 = mysql_real_escape_string($_POST['droit2']);
else $droit2=0;

if (isset($_POST['droit3'])&&$_POST['droit3']==1)
	$droit3 = mysql_real_escape_string($_POST['droit3']);
else $droit3=0;

if (isset($_POST['droit4'])&&$_POST['droit4']==1)
	$droit4 = mysql_real_escape_string($_POST['droit4']);
else $droit4=0;

$droits = $droit1.$droit2.$droit3.$droit4;

$options = mysql_real_escape_string($_POST['options']);
$telephone = mysql_real_escape_string($_POST['telephone']);
$sms = mysql_real_escape_string($_POST['sms']);

//------- Requête de mise à jour -----
if (isset($_POST['nom'])){
	mysql_query("UPDATE utilisateurs 
					SET nom = '$nom', 
						email ='$mail', 
						jabberid = '$jabberid', 
						droits = '$droits', 
						options = '$options', 
						telephone = '$telephone', 
						sms = '$sms'  
					WHERE id = '$id_utilisateur'
				") or die (mysql_error());			
}
//---------------------------------------

//mysql_close();

echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	

?>
