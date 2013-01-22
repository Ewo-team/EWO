<?php
session_start();

$root_url = "..";

include("../conf/master.php");
include("../persos/fonctions.php");
include("./fonctions.php");
// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');

ControleAcces('utilisateur',1);

if(isset($_GET['action'])){
	$action = $_GET['action'];

	$perso_id = $_GET['perso_id'];
	if(isset($_GET['des_attaque']))
		$val_att = $_GET['des_attaque'];
		else $val_att = 1;
		
	if((!is_numeric($perso_id) || !is_numeric($val_att) || $action != "maj_des") && $action != "maj_esq_mag"){
		mysql_close($ewo_bdd);
		exit;
		}
	$isperso=false;
	for($inc=1; $inc<=$_SESSION['persos']['inc']; $inc++){
		if($_SESSION['persos']['id'][$inc]==$perso_id){
			$isperso=true;
			}
		}
	if(!$isperso){
		$perso_id=$_SESSION['persos']['current_id'];
		}
		
	$race_grade = recup_race_grade($perso_id);
	$race 		= $race_grade['race_id'];
	$grade 		= $race_grade['grade_id'];
	$galon 		= $race_grade['galon_id'];
	
	if($action == "maj_des"){
		$caracs_max = caracs_base_max ($perso_id, $race, $grade);
		$caracs_max	= $caracs_max['des'];
		/*
		@ Mettre a jour les des
		*/
		$des=recup_carac($perso_id, array('maj_des'));
		if(!$des['maj_des']){
			if ($action == "maj_des"){
				if (isset($val_att)){
					if($val_att>=$caracs_max){
					$val_att=$caracs_max-1;
					}
					maj_carac($perso_id, "des_attaque", $val_att);
					maj_carac($perso_id, "maj_des", 1);
				}
			}
		}
	}
	if($action == "maj_esq_mag"){
		maj_esq_mag($perso_id, 1, 0);
		}
}

if(isset($_GET['menu'])){
$color="";
for($inci=1; $inci<=$_SESSION['persos']['inc']; $inci++){	
	$datetour = $_SESSION['persos']['date_tour'][$inci];
	$datetour = strtotime($datetour);

	$time = time();
	if ($time >= $datetour){
	$color = $color."$inci|#f12727";
	}else $color = $color."$inci|";
	if($inci!=$_SESSION['persos']['inc']){
		$color = $color."|";
		}
	}
echo $color;
}

if(isset($_GET['offset_width'])){
	$_SESSION['offset_width']=$_GET['offset_width'];
	}
	
mysql_close($ewo_bdd);
?>
