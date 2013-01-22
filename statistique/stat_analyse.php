<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

if(!empty($_POST['debut_date']) AND !empty($_POST['fin_date'])){
	// Paramètres de connexion à la base de données
	bdd_connect('ewo');
	$date_debut = mysql_real_escape_string($_POST['debut_date']);
	$date_fin = mysql_real_escape_string($_POST['fin_date']);

	$_SESSION['date_debut'] = $date_debut;
	$_SESSION['date_fin'] = $date_fin;

	// Date de fin
	if($date_fin == "now"){
		$ret_date_fin = time();
	}elseif(preg_match('#(.*)/(.*)/(.*)#', $date_fin, $datetab)){
		$ret_date_fin = mktime (18, 0, 0, $datetab[2], $datetab[1], $datetab[3]);	
	}else{
		$ret_date_fin = "rien";
	}

	// Date de debut
	if(preg_match('#day-(.*)#', $date_debut, $datetab)){
		if($ret_date_fin != "rien"){
			$ret_date_debut = $ret_date_fin - $datetab[1]*86400;
		}else{
			$ret_date_debut = "rien";
		}
	}elseif(preg_match('#(.*)/(.*)/(.*)#', $date_debut, $datetab)){
		$ret_date_debut = mktime (0, 0, 0, $datetab[2], $datetab[1], $datetab[3]);		
	}else{
		$ret_date_debut = "rien";
	}

	// Affichage
	if($ret_date_debut <= $ret_date_fin AND $ret_date_debut > 1270072755){
		$_SESSION['stat']['default'] = 'ok';
		$_SESSION['stat']['ret_date_fin'] = $ret_date_fin;
		$_SESSION['stat']['ret_date_debut'] = $ret_date_debut;
		/*	
		echo "<p>Date de debut : ";
		echo $ret_date_debut;
		echo '<br />';
		echo date('d-M-Y', $ret_date_debut);
		echo '<br />';

		echo "Date de fin : ";
		echo $ret_date_fin;
		echo '<br />';
		echo date('d-M-Y', $ret_date_fin);
		echo '</p>';	
		$temps = round(($ret_date_fin-$ret_date_debut)/24/60/60);

		echo "Nombre de jours : ".$temps;
		*/
	}else{
		$ret_date_fin =  time();	
		$ret_date_debut = $ret_date_fin - 86400*10;
		$_SESSION['stat']['default'] = 'nok';
		$_SESSION['stat']['ret_date_fin'] = $ret_date_fin;
		$_SESSION['stat']['ret_date_debut'] = $ret_date_debut;		
		//echo "<p>Imposible de prendre en compte ces dates</p>";
	}
	header("location:index.php");
}
?>
