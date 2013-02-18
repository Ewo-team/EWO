<?php

namespace jeu\classement;
use \conf\ConnecteurDAO as ConnecteurDAO;

/**
 * Classement position
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */

require_once __DIR__ . '/../../conf/master.php';

$ewobdd = ConnecteurDAO::getInstance()->getConn();

if (isset($_POST['mat'])){
	$mat = $_POST['mat'];
}elseif(isset($_GET['mat'])){
	$mat = $_GET['mat'];
}

if (isset($_POST['pseudo'])){
	$pseudo = $_POST['pseudo'];
}elseif(isset($_GET['pseudo'])){
	$pseudo = $_GET['pseudo'];
}

if (isset($_POST['date'])){
	$date = $_POST['date'];
}elseif(isset($_GET['date'])){
	$date = $_GET['date'];
} else {
	$date = date("Y-m-d");
}

if(isset($mat) || isset($pseudo)) {
	// recup l'xp
	$query = "SELECT count(*) FROM classement l WHERE date=:date AND ";
	if(isset($mat)) {
		$query .= "(SELECT xp FROM classement d WHERE mat=:val AND l.date = d.date LIMIT 1)";
		$val = $mat;
	} else {
		$query .= "(SELECT xp FROM classement d WHERE pseudo=:val AND l.date = d.date LIMIT 1)";	
		$val = $pseudo;
	}

	
	$state = $ewobdd->prepare($query);
	$state->bindValue(":date", $date);
	$state->bindValue(":val", $val);
	$state->execute();
	
	$info = $state->fetch(\PDO::FETCH_NUM);
	
	$rang = $info[0];

	
	// recup nombre de perso avant

	$page = ceil($rang / 50);
	
	header('Location: .?perso_id=0&page='.$page.'&croissant=DESC&grade_ord=-1&race=0&nb_el=50&highlight='.$mat);
}
?>
