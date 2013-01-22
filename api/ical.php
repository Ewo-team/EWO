<?php
session_start();
$root_url = "..";
include ("ApiDAO.php");

$conn = ApiDAO::getInstance();
	
function entete($utilisateur_id) {
	header("Content-Type: text/calendar; charset=utf-8");
	header("Content-Disposition: inline; filename=Cal".$utilisateur_id."_".date( 'YmdHis' ).'.ics');
	echo "BEGIN:VCALENDAR" . PHP_EOL;
	echo "VERSION:2.0" . PHP_EOL;
	echo "PRODID:-//hacksw/handcal//NONSGML v1.0//EN" . PHP_EOL;   
}

function fin() {
	echo "END:VCALENDAR";
}

function persoActivable($nom, $dateactive, $raison = "est en attente d'activation") {
	$debut = date("Ymd\THis\0", $dateactive);
	$fin = date("Ymd\THis\0", $dateactive+5*60);	

	echo "BEGIN:VEVENT" . PHP_EOL;
	echo "DTSTART:$debut" . PHP_EOL;
	echo "DTEND:$fin" . PHP_EOL;	
	echo "SUMMARY:$nom $raison" . PHP_EOL;
	echo "END:VEVENT" . PHP_EOL;	
}

function persoPasActivable($nom,$dateactive) {
	$debut = date("Ymd\THis\0", $dateactive);
	$fin = date("Ymd\THis\0", $dateactive+5*60);
	$alarme = date("Ymd\THis\0", $dateactive-5*60);

	echo "BEGIN:VEVENT" . PHP_EOL;
	echo "DTSTART:$debut" . PHP_EOL;
	echo "DTEND:$fin" . PHP_EOL;
	echo "SUMMARY:Activation de $nom" . PHP_EOL;

    echo "BEGIN:VALARM" . PHP_EOL;
    echo "ACTION:DISPLAY" . PHP_EOL;
    echo "TRIGGER:$alarme" . PHP_EOL;
    echo "END:VALARM" . PHP_EOL;
	
	echo "END:VEVENT" . PHP_EOL;	
}

if(isset($_GET['k'])) {
	$cle = $_GET['k'];

	$now = time();
	
	$result = $conn->SelectKey($cle);

	if($result) {
		if($result['niveau'] == "full") {
			$utilisateur = $result['utilisateur_id'];
		} else {
			exit();
		}
	} else {
		exit();
	}

	if($result = $conn->SelectPersos($utilisateur)) {
		entete($utilisateur);
		foreach($result as $ligne) {
			if(!isset($ligne['carte_id'])) {
				// le perso est désincarné
				persoActivable($ligne['nom'], time(), "n'est pas incarné!");
			} elseif(strtotime($ligne['date_tour']) < $now) {
				// Le perso doit être activé
				persoActivable($ligne['nom'], strtotime($ligne['date_tour']));
			} else {
				persoPasActivable($ligne['nom'],strtotime($ligne['date_tour']));
			}
		}
		fin();
	}
}

?>
