<?php
session_start();
header("content-Type: image/png");

include("../conf/connect.conf.php");
include("../persos/fonctions.php");
include("./fonctions.php");
// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');


// Recupération des données
        
$id = $_SESSION['persos']['id'][0];

$pos_x_perso 	= $_SESSION['persos']['pos_x'][$id];
$pos_y_perso 	= $_SESSION['persos']['pos_y'][$id];
$carte_pos 		= $_SESSION['persos']['carte'][$id];


// Données de la carte courante
$x_min_visible = $_SESSION['x_min_visible'];
$x_max_visible = $_SESSION['x_max_visible'];

$y_min_visible = $_SESSION['y_min_visible'];
$y_max_visible = $_SESSION['y_max_visible'];

$circ_x = $_SESSION['circ'][0];
$circ_y = $_SESSION['circ'][1];

if($circ_x){
	$taille_x=$x_max_visible - $x_min_visible;
	}
	else $taille_x=$x_max_visible - $x_min_visible + 1;

if($circ_y){
		$taille_y=$y_max_visible - $y_min_visible;
		}
		else $taille_y=$y_max_visible - $y_min_visible + 1;
		
$mini_carte = imagecreate($taille_x , $taille_y);
$maxi_carte = imagecreate(200 , imagesy($mini_carte)*200/imagesx($mini_carte) );

$fond = imagecolorallocate($mini_carte, 230,  230, 150);

$humain	= imagecolorallocate($mini_carte,0,200,0);
$roi	= imagecolorallocate($mini_carte,0,80,0);
$paria	= imagecolorallocate($mini_carte,200,0,200);
$AA		= imagecolorallocate($mini_carte,0,0,80);
$ange	= imagecolorallocate($mini_carte,0,0,200);
$SD		= imagecolorallocate($mini_carte,80,0,0);
$demon	= imagecolorallocate($mini_carte,200,0,0);
$black	= imagecolorallocate($mini_carte,0,0,0);
$bouclier   = imagecolorallocate($mini_carte,0,100,100);

//Recupération des personnages de la carte
$rchch_x= "(pos_x>='$x_min_visible' AND pos_x<='$x_max_visible')";
$rchch_y= "( pos_y>='$y_min_visible' AND pos_y<='$y_max_visible')";

$sql="SELECT * FROM damier_persos WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$reponse = mysql_query($sql) or die(mysql_error());
while($persos=mysql_fetch_array($reponse)){
	$sql_info="SELECT race_id AS race_id, grade_id AS grade_id FROM persos WHERE id='".$persos['perso_id']."'";
	$rep_infos = mysql_query($sql_info) or die(mysql_error());
	$infos=mysql_fetch_array($rep_infos);

	switch($infos['race_id']){
		case 1 :
			if($infos['grade_id']>=4){
				$color=$roi;			
				}
				else{
					$color=$humain;	
					}
		break;
			case 2 :
			if($infos['grade_id']>4){
				$color=$paria;			
				}
				else{
					$color=$paria;	
					}
		break;
			case 3 :
			if($infos['grade_id']>4){
				$color=$AA;	
				}
				else{
					$color=$ange;
					}
		break;
			case 4 :
			if($infos['grade_id']>4){
				$color=$SD;			
				}
				else{
					$color=$demon;
					}
		break;
		default:	
			$color=$black;	
			}
	if($circ_y){
		imagesetpixel($mini_carte, $persos['pos_x']-$x_min_visible-1, imagesy($mini_carte)-($persos['pos_y']-$y_min_visible), $color);
		}
		else {
			imagesetpixel($mini_carte, $persos['pos_x']-$x_min_visible-1, imagesy($mini_carte)-($persos['pos_y']-$y_min_visible+1), $color);
			}
	}
	
$sql="SELECT * FROM damier_porte WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$reponse = mysql_query($sql) or die(mysql_error());
while($infos=mysql_fetch_array($reponse)){
	$color=$black;	
	if($circ_y){
		for($inci=0;$inci<=3;$inci++){
			for($incj=0;$incj<=3;$incj++){
				imagesetpixel($mini_carte, $infos['pos_x']+$inci-$x_min_visible-1, imagesy($mini_carte)-($infos['pos_y']-$incj-$y_min_visible), $color);
				}
			}
		}
		else {
			for($inci=0;$inci<=3;$inci++){
				for($incj=0;$incj<=3;$incj++){
					imagesetpixel($mini_carte, $infos['pos_x']+$inci-$x_min_visible-1, imagesy($mini_carte)-($infos['pos_y']-$incj-$y_min_visible+1), $color);
					}
				}
		}
	}

$sql="SELECT * FROM damier_bouclier WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$reponse = mysql_query($sql) or die(mysql_error());
while($infos=mysql_fetch_array($reponse)){
	$color=$bouclier;
	$taille=$infos['type_id'];
	if($circ_y){
		for($inci=0;$inci<$taille;$inci++){
			for($incj=0;$incj<$taille;$incj++){
				imagesetpixel($mini_carte, $infos['pos_x']+$inci-$x_min_visible-1, imagesy($mini_carte)-($infos['pos_y']-$incj-$y_min_visible), $color);
				}
			}
		}
		else {
			for($inci=0;$inci<$taille;$inci++){
				for($incj=0;$incj<$taille;$incj++){
					imagesetpixel($mini_carte, $infos['pos_x']+$inci-$x_min_visible-1, imagesy($mini_carte)-($infos['pos_y']-$incj-$y_min_visible+1), $color);
					}
				}
		}
	}

	
imagecopyresized($maxi_carte, $mini_carte, 0, 0, 0, 0, imagesx($maxi_carte), imagesy($maxi_carte), imagesx($mini_carte), imagesy($mini_carte));

imagepng($maxi_carte);
imagedestroy($mini_carte);
imagedestroy($maxi_carte);

mysql_close($ewo_bdd);
?>