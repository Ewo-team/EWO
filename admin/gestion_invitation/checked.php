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

if (isset($_POST['id'])){
$id = $_POST['id'];
	foreach($id as $id_id){
		mysql_query("UPDATE `invitations` SET `distribue` = '1' WHERE `invitations`.`id` = $id_id");
	}
	header("location:index.php");
}else{
	header("location:index.php");
}
?>
