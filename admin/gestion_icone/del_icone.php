<?php
//-- Header --
$root_url = "./../..";
include __DIR__ . '/../../conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if (isset($_GET['id'])){
		
	$id = mysql_real_escape_string($_GET['id']);
	
	$sql="SELECT icone_url FROM icone_persos WHERE id='$id'";
	$icone = mysql_query($sql);
	$icone = mysql_fetch_array ($icone);
	$icone_del = $icone['icone_url'];
	
	unlink($root_url.'/images/'.$icone_del);
	
	$sql="DELETE FROM icone_persos WHERE id='$id'";
  mysql_query($sql);
	
	echo "<script language='javascript' type='text/javascript' >document.location='./'</script>";exit;
	
	}else{
		echo "<h2>suppression message</h2><p align='center'>Vous n'etes pas autoriser a effectuer cette action.</p>";
	include($root_url."/template/footer_new.php");
}


?>
