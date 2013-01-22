<?php
session_start(); 
$root_url = "./../..";
//-- Header --
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

$num = mysql_real_escape_string($_POST['nb_num']);

if(!empty($num) AND (is_numeric($num))) {
	for($i=0;$i<$num;$i++){
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$datetime = "$date $time";
	
		$rand = rand(99,9999);
		$code_validation = md5($datetime.$rand.$num);
		
		$sql = mysql_query("INSERT INTO invitations (id, numero, date, distribue) VALUES ('', '$code_validation', '$date', '0')") or die (mysql_error());
	}
	header("location:index.php");
}else{
	echo 'non numeric';exit;
}
?>
