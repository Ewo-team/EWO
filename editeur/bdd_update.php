<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
if (ControleAcces('admin',0) == false){
	echo "acces denied";exit;
}
/*-----------------------*/

if (isset($_GET['where'])){
	// Paramètres de connexion à la base de données
	$sql_ewo = bdd_connect('ewo');
	
	$txt = $_GET['text'];
	$txt = mysql_real_escape_string(htmlspecialchars($txt));
	
		$sql="UPDATE ".$_GET['table']." SET 
					".$_GET['champ']." = '".$txt."' 
					WHERE id = '".$_GET['where']."'";
		mysql_query($sql) or die(mysql_error());
		mysql_close($sql_ewo);
		echo "update close";
		
}else{
	echo 'lien null';
}
?>
