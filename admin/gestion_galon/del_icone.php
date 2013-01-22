<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
//------------

/*-- Connexion basic requise --*/
if (Controle('admin') != TRUE){
 echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";exit;}
/*-----------------------------*/

if (isset($_GET['id'])){
		
	$id = $_GET['id'];
	
	$sql="SELECT icone_url FROM icone_galons WHERE id='$id'";
	$icone = mysql_query($sql);
	$icone = mysql_fetch_array ($icone);
	$icone_del = $icone['icone_url'];
	
	unlink($root_url.'/images/'.$icone_del);
	
	$sql="DELETE FROM icone_galons WHERE id='$id'";
  mysql_query($sql);
	
	echo "<script language='javascript' type='text/javascript' >document.location='./'</script>";exit;
	
	}else{
		echo "<h2>suppression message</h2><p align='center'>Vous n'etes pas autoriser a effectuer cette action.</p>";
	include($root_url."/template/footer_new.php");
}
?>
