<?php
session_start();
include ("/usr/local/www/ewo/current/conf/connect.conf.php");
include ("/usr/local/www/ewo/current/conf/fonctions.php");

$ewo = bdd_connect('ewo');

/*$nb_ange_total = mysql_query("SELECT FROM WHERE '");
mysql_fetch_row($verif_compte);*/

/*
* Race: 3 Ange, 4 Demon, 1 Humain
* Plan: 1 Terre, 2 Enfer, 3 Paradis
* Grade: de 0 à 5.	
*/

$race = array("ange" => 3, "demon" => 4, "humain" => 1);
$grade = array("g0"=>0,"g1"=>1,"g2"=>2,"g3"=>3,"g4"=>4,"g5"=>5);
$plan = array ("terre"=>1,"enfer"=>2,"paradis"=>3);


foreach ($race as $krace => $valuerace){
	foreach($grade as $kgrade => $valuegrade){
		foreach($plan as $kplan => $valueplan){
			$personnages[$krace][$kgrade][$kplan] = statistique_persos_vivant($valuerace,$valuegrade,$valueplan);
		}
	}
}

//print_r($personnages);exit;
//echo $personnages['ange']['g0']['terre'];exit;
// race, grade, plan
//echo statistique_persos_vivant(3,1,2);exit;

	$nb_ange = statistique_perso_inscrit(3);
	$nb_demon = statistique_perso_inscrit(4);
	$nb_humain = statistique_perso_inscrit(1);
	
	$nb_joueur = statistique_joueur_inscrit();
	$time = time();

	mysql_query("INSERT INTO `stat_popvivante` (`id`, `date`, `nb_joueur_total`, `ange_g0_terre`, `ange_g0_enfer`, `ange_g0_paradis`, `ange_g1_terre`, `ange_g1_enfer`, `ange_g1_paradis`, `ange_g2_terre`, `ange_g2_enfer`, `ange_g2_paradis`, `ange_g3_terre`, `ange_g3_enfer`, `ange_g3_paradis`, `ange_g4_terre`, `ange_g4_enfer`, `ange_g4_paradis`, `ange_g5_terre`, `ange_g5_enfer`, `ange_g5_paradis`, `ange_total`, `demon_g0_terre`, `demon_g0_enfer`, `demon_g0_paradis`, `demon_g1_terre`, `demon_g1_enfer`, `demon_g1_paradis`, `demon_g2_terre`, `demon_g2_enfer`, `demon_g2_paradis`, `demon_g3_terre`, `demon_g3_enfer`, `demon_g3_paradis`, `demon_g4_terre`, `demon_g4_enfer`, `demon_g4_paradis`, `demon_g5_terre`, `demon_g5_enfer`, `demon_g5_paradis`, `demon_total`, `humain_g0_terre`, `humain_g0_enfer`, `humain_g0_paradis`, `humain_g1_terre`, `humain_g1_enfer`, `humain_g1_paradis`, `humain_g2_terre`, `humain_g2_enfer`, `humain_g2_paradis`, `humain_g3_terre`, `humain_g3_enfer`, `humain_g3_paradis`, `humain_g4_terre`, `humain_g4_enfer`, `humain_g4_paradis`, `humain_g5_terre`, `humain_g5_enfer`, `humain_g5_paradis`, `humain_total`)
							VALUES ('',
							 '$time',
							 '$nb_joueur', 
								".$personnages['ange']['g0']['terre'].",
								".$personnages['ange']['g0']['enfer'].",
								".$personnages['ange']['g0']['paradis'].",
								".$personnages['ange']['g1']['terre'].",
								".$personnages['ange']['g1']['enfer'].",
								".$personnages['ange']['g1']['paradis'].",
								".$personnages['ange']['g2']['terre'].",
								".$personnages['ange']['g2']['enfer'].",
								".$personnages['ange']['g2']['paradis'].",
								".$personnages['ange']['g3']['terre'].",
								".$personnages['ange']['g3']['enfer'].",
								".$personnages['ange']['g3']['paradis'].",
								".$personnages['ange']['g4']['terre'].",
								".$personnages['ange']['g4']['enfer'].",
								".$personnages['ange']['g4']['paradis'].",
								".$personnages['ange']['g5']['terre'].",
								".$personnages['ange']['g5']['enfer'].",
								".$personnages['ange']['g5']['paradis'].",
							 '$nb_ange', 
								".$personnages['demon']['g0']['terre'].",
								".$personnages['demon']['g0']['enfer'].",
								".$personnages['demon']['g0']['paradis'].",
								".$personnages['demon']['g1']['terre'].",
								".$personnages['demon']['g1']['enfer'].",
								".$personnages['demon']['g1']['paradis'].",
								".$personnages['demon']['g2']['terre'].",
								".$personnages['demon']['g2']['enfer'].",
								".$personnages['demon']['g2']['paradis'].",
								".$personnages['demon']['g3']['terre'].",
								".$personnages['demon']['g3']['enfer'].",
								".$personnages['demon']['g3']['paradis'].",
								".$personnages['demon']['g4']['terre'].",
								".$personnages['demon']['g4']['enfer'].",
								".$personnages['demon']['g4']['paradis'].",
								".$personnages['demon']['g5']['terre'].",
								".$personnages['demon']['g5']['enfer'].",
								".$personnages['demon']['g5']['paradis'].",
							 '$nb_demon', 
								".$personnages['humain']['g0']['terre'].",
								".$personnages['humain']['g0']['enfer'].",
								".$personnages['humain']['g0']['paradis'].",
								".$personnages['humain']['g1']['terre'].",
								".$personnages['humain']['g1']['enfer'].",
								".$personnages['humain']['g1']['paradis'].",
								".$personnages['humain']['g2']['terre'].",
								".$personnages['humain']['g2']['enfer'].",
								".$personnages['humain']['g2']['paradis'].",
								".$personnages['humain']['g3']['terre'].",
								".$personnages['humain']['g3']['enfer'].",
								".$personnages['humain']['g3']['paradis'].",
								".$personnages['humain']['g4']['terre'].",
								".$personnages['humain']['g4']['enfer'].",
								".$personnages['humain']['g4']['paradis'].",
								".$personnages['humain']['g5']['terre'].",
								".$personnages['humain']['g5']['enfer'].",
								".$personnages['humain']['g5']['paradis'].",
							 '$nb_humain')")or die(mysql_error());
//echo "Done";

mysql_close($ewo);
?>


