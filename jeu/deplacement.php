<?php
session_start();

$id = $_SESSION['persos']['id'][0];

$lock = rand();
$_SESSION['persos']['mouv_lock'][$id] = $lock;

$root_url = "..";
include ($root_url."/conf/master.php");

include_once($root_url.'/eventsManager/eventsManager.php');
include_once($root_url.'/event/eventManager.php');
include_once($root_url.'/event/special.php');

/*-- Connexion requise --*/
ControleAcces('utilisateur',1);
/*-----------------------*/


include("../persos/fonctions.php");
include("./fonctions.php");

// Param�tres de connexion � la base de donn�es
$ewo_bdd_connect = bdd_connect('ewo');

$perso_id = $_SESSION['persos']['current_id'];


$is_spawn=false;

$sql="SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
$resultat = mysql_query ($sql) or die (mysql_error());
if($pos = mysql_fetch_array ($resultat)){

	$is_spawn=true;

}else {
	header("location:./index.php?perso_id=".$id."#p");
	//echo "<script language='javascript' type='text/javascript' >document.location='./index.php?perso_id=$id#p'</script>";exit;
}

if($is_spawn){
	activ_tour($_SESSION['persos']['id'][0]);




	mysql_query('SET autocommit=0;');
	mysql_query("START TRANSACTION;");
	$caracs = calcul_caracs($_SESSION['persos']['current_id']);
	$_SESSION['persos']['pos_x'][$id] = $pos["pos_x"];
	$_SESSION['persos']['pos_y'][$id] = $pos["pos_y"];
	$_SESSION['persos']['carte'][$id] = $pos["carte_id"];
	if($caracs['mouv'] > 0){
		if($lock == $_SESSION['persos']['mouv_lock'][$id]) {
			maj_pos($id, $caracs);
		}
	}
	$caracs = calcul_caracs($_SESSION['persos']['current_id']);
	if ($caracs['pv'] <=0) {
		$events = SPECIAL_EVENT::$INDEX;
		$em = new eventManager();
		$ev1 = $em->createEvent('special');
		$ev1->setSource($perso_id, 'perso');
		$ev1->infos->addPublicInfo('m',$events['mort_lave']);
		desincarne($perso_id);
	}	
	mysql_query('COMMIT;');
	mysql_query('SET autocommit=1;');
	mysql_close();
}
//print_r($_SESSION);
unset($_SESSION['persos']['mouv_lock'][$id]);
header("location:./index.php?perso_id=".$id."#p");
//echo "<script language='javascript' type='text/javascript' >document.location='./index.php?perso_id=$id#p'</script>";exit;

//mysql_close();
?>
