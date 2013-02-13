<?php

use \conf\ConnecteurDAO as ConnecteurDAO;

include (SERVER_ROOT . "/conf/tableau_medaille.php");


function ajouteMedaille($id,$mat) {
	$conn = ConnecteurDAO::getInstance();
	
	$sql = "INSERT DELAYED INTO medailles (id_perso,id_medaille,nombre) VALUES ($mat,$id,1) ON DUPLICATE KEY UPDATE nombre=nombre+1;";
	//echo $sql;
	$conn->exec($sql);
	
	$conn = null;
}

?>