<?php

include ("tableau_medaille.php");
require_once ($root_url . "/conf/ConnecteurDAO.php");

function ajouteMedaille($id,$mat) {
	$conn = ConnecteurDAO::getInstance();
	
	$sql = "INSERT DELAYED INTO medailles (id_perso,id_medaille,nombre) VALUES ($mat,$id,1) ON DUPLICATE KEY UPDATE nombre=nombre+1;";
	//echo $sql;
	$conn->exec($sql);
	
	$conn = null;
}

?>