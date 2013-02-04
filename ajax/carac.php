<?php

require_once __DIR__ . '/conf/master.php';

if(!isset($_SESSION['utilisateur']['id'])){
	header("location:../index.php");
}

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

include SERVER_ROOT . '/persos/fonctions.php';
include SERVER_ROOT . '/jeu/fonctions.php';

$id_utilisateur = $_SESSION['utilisateur']['id'];
//print_r($_SESSION);
if(isset($_GET['perso_id'])){
	if ($_GET['perso_id'] <= $_SESSION['persos']['inc']){
		$inc = mysql_real_escape_string($_GET['perso_id']);
	} else {
		$inc = 1;
	}
	$_SESSION['persos']['current_id'] = $_SESSION['persos']['id'][$inc];
	$_SESSION['persos']['id'][0]=$inc;

	$caracs	= calcul_caracs();

	$race		= $_SESSION['persos']['race'][$inc] ;
	$grade		= $_SESSION['persos']['grade'][$inc];

	$caracs_max = caracs_base_max ($_SESSION['persos']['current_id'], $race, $grade);

	$tab_recup_malus_actu = recup_malus($caracs['recup_pv'], $caracs_max['pv']);
	$tab_recup_malus = recup_malus($caracs_max['recup_pv'], $caracs_max['pv']);

	$valeurs = array(
		'pv' => array(
			'max' => $caracs_max['pv'],
			'actu' => $caracs['pv'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'malus' => array(
			'actu' => $caracs['malus_def'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'pa' => array(
			'max' => ($caracs_max['pa']+$caracs_max['pa_dec']/10),
			'actu' => ($caracs['pa']+$caracs['pa_dec']/10),
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'mouv' => array(
			'max' => $caracs_max['mouv'],
			'actu' => $caracs['mouv'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'res_mag' => array(
			'actu' => $caracs['res_mag'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'recup_pv' => array(
			'max' => floor($caracs_max['recup_pv']*$caracs_max['pv']/100),
			'actu' => floor($caracs['recup_pv']*$caracs_max['pv']/100),
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'recup_malus' => array(
			'max' => $tab_recup_malus["recup_fixe"],
			'actu' => $tab_recup_malus_actu["recup_fixe"],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'force' => array(
			'max' => $caracs_max['force'],
			'actu' => $caracs['force'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'percept' => array(
			'max' => $caracs_max['perception'],
			'actu' => $caracs['perception'],
			'classcolor' => 'color_vert',
			'taille' => 100
		),
		'magie' => array(
			'max' => $caracs_max['magie'],
			'actu' => $caracs['magie'],
			'classcolor' => 'color_vert',
			'taille' => 100
		)
	);

	foreach($valeurs as $key => $value) {
		switch($key) {
			case 'malus':
				if ($caracs['malus_def'] == 0){
					$taille = 100;
					$classe = "color_vert";
				}else{
					$taille = 0;
					$classe = $valeurs['pv']['classcolor'];
				}
				break;
			case 'res_mag':
				if ($caracs['res_mag'] > $caracs_max['res_mag']){
					$taille = 100;
					$classe = "color_depass";
				}else{
					$taille = 100+$caracs['res_mag']-$caracs_max['res_mag'];
					$classe = "color_vert";
				}
				break;
			default:
				if($value['max'] != 0) {
					$val = $value['actu']/$value['max']*100;
				} else {
					$val = 100;
				}
				if ($val > 100){
					$taille = 100;
					$classe = "color_depass";
				}else{
					$classe = "color_vert";
					$taille = $val;
				}
				break;
		}

		$valeurs[$key]['classcolor'] = $classe;
		$valeurs[$key]['taille'] = $taille;
	}

	$valeurs['xp'] = array(
		'px' => $caracs['px'],
		'pi' => $caracs['pi']
	);

	//print_r($valeurs);
	echo json_encode($valeurs);



}



