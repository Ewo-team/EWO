<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
if (ControleAcces('utilisateur',0) == false){
	echo "false";exit;
}
/*-----------------------*/

if (isset($_GET['mdj'])){
	// Paramètres de connexion à la base de données
	$ewo = bdd_connect('ewo');
	
if (empty($_GET['mdj'])){
	$mdj = '';
	$erreur = false;
}else{

	$mdj = mysql_real_escape_string($_GET['mdj']);
	$mdj = htmlspecialchars($mdj);
	
	$mdj = wordwrap($mdj, 25, "<br />", true);
	$erreur = false;	
	/*
	$test = str_word_count($mdj, 1, '0123456789./*-+,;:!§/.?*ù$^µ%£¨~#{}[]()|_à@=');
	foreach($test as $mot){
		$taille = strlen($mot);
		if($taille > 20){
			$erreur = true;
		}else{
			$erreur = false;
		}
	}
	*/
}
	if ($erreur == false){
		
		$utilisateur_id = $_SESSION['utilisateur']['id'];
		$perso_id = $_SESSION['persos']['current_id'];
		 		 
		mysql_query("UPDATE persos SET mdj = '$mdj' WHERE utilisateur_id = '$utilisateur_id' AND id = '$perso_id'");
		if(mysql_affected_rows() > 0)
		{
			$time = ceil(time() / 119);
			$sql = "REPLACE INTO  `ewo`.`persos_mdj` (`id` ,`perso_id` ,`date` ,`message`)
					VALUES ('$time',  '$perso_id', CURRENT_TIMESTAMP ,  '$mdj');";		
			mysql_query($sql);
		}
		echo $mdj;
		//$reponse = array('utilisateur' => $utilisateur_id, 'perso' => $perso_id, 'mdj' => $mdj);
		//echo json_encode($reponse);
	}else{
		echo "false";
	}
		mysql_close($ewo);
}else{
	echo "null";
}
?>
