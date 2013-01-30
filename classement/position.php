<?php
/**
 * Classement position
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
use \conf\ConnecteurDAO as ConnecteurDAO;

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
}

if(isset($mat) || isset($pseudo)) {
	// recup l'xp
	$query = "SELECT count(*) FROM classement l WHERE date=? AND ";
	if(isset($mat)) {
		$query = "(SELECT xp FROM classement d WHERE mat=? AND l.date = p.date LIMIT 1)";
		$val = $mat;
	} else {
		$query = "(SELECT xp FROM classement d WHERE pseudo=? AND l.date = p.date LIMIT 1)";	
		
	}
	$state = $ewobdd->prepare($query);
	$state->bindValue(1, $date);
	$state->bindValue(2, $val);
	$state->execute();
	
	$info = $state->fetch(PDO::FETCH_NUM);
	$rang = $info[0];

	
	// recup nombre de perso avant

	$page = ceil($rang / 50);
	
	header('Location: classement_new.php?perso_id=0&page='.$page.'&croissant=DESC&grade_ord=-1&race=0&nb_el=50&highlight='.$mat);
}
?>
