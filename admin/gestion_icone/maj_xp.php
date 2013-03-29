<?php
//-- Header --
$root_url = "./../..";
include __DIR__ . '/../../conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if ((isset($_POST['icone_id'])) AND (isset($_POST['px']))){

$xp = mysql_real_escape_string($_POST['px']);
$xp_max = mysql_real_escape_string($_POST['px_max']);
$icone_id = mysql_real_escape_string($_POST['icone_id']);   
   
	mysql_query("UPDATE icone_persos SET xp_min = '$xp', xp_max = '$xp_max' WHERE id = '$icone_id'");
	
  echo "<script language='javascript' type='text/javascript' >document.location='".$_SERVER['HTTP_REFERER']."'</script>";exit;	
}else{
		echo "<h2>Modfication de l'xp du perso</h2><p align='center'>Vous n'etes pas autoriser a effectuer cette action.</p><p align='center'>[<a href='".$_SERVER['HTTP_REFERER']."'>Retour</a>]</p>";
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
}

?>
