<?php 
$admin_mode = 1;
$version_test = 1;
	
if(isset($_POST['race'])) {
	$admin_race = $_POST['race'];
	$admin_grade = $_POST['grade'];
	$admin_galon = $_POST['galon'];
} else {
	$admin_race = 1;
	$admin_grade = 0;
	$admin_galon = 0;
}

if(isset($_POST['val_ini'])) {

	$coutPvBase      = $_POST['pv_b'];
	$coutRecupPvBase = $_POST['recup_b'];
	$coutMouvBase    = $_POST['mouv_b'];
	$coutForceBase   = $_POST['force_b'];
	$coutNvMagBase   = $_POST['nvmag_b'];
	$coutPercBase    = $_POST['per_b'];
	$coutPaBase      = $_POST['pa_b'];
} else {
	$coutPvBase      = 50;
	$coutRecupPvBase = 100;
	$coutMouvBase    = 80;
	$coutForceBase   = 50;
	$coutNvMagBase   = 100;
	$coutPercBase    = 100;
	$coutPaBase      = 750;
}
$addNvMag = 200;//Valeur à ajouter au cout précédent	

if(isset($_POST['val_raison'])) {
	$pv_r = $_POST['pv_r'];
	$recup_r = $_POST['recup_r'];
	$mouv_r = $_POST['mouv_r'];
	$force_r = $_POST['force_r'];
	$nvmag_r = $_POST['nvmag_r'];
	$per_r = $_POST['per_r'];
	$pa_r = $_POST['pa_r'];
} else {
	$pv_r = 5;
	$recup_r = 10;
	$mouv_r = 8;
	$force_r = 5;
	$nvmag_r = 300;
	$per_r = 10;
	$pa_r = 250;
}

include("./../../persos/upgrades/index.php");
?>
