<?php
session_start();
$root_url = "./../..";

//-- Header --
include($root_url."/conf/master.php");
include ("Actions.class.php");
include ("Effet.class.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if(isset($_POST['flag'])) {
	$action = new Actions();
	$effets = array();

	// Valeurs par défaut
	$action->lanceur = 0;
	$action->cible = 0;
		
	foreach($_POST as $cle => $valeur) {
		switch($cle) {
			case 'effet_type_lanceur':
			case 'effet_type_cible':
			case 'effet_valeur_lanceur':
			case 'effet_valeur_cible':
				foreach($valeur as $k => $v) {
					$effets[$cle][$k] = $v;
				}	
				break;	
			case 'nom1':				
			case 'nom2':				
			case 'nom3':				
			case 'nom4':				
			case 'flag':
				break;
			case 'nomMultiple' :
				if($valeur == 'simple')
				{
					$action->nom = $_POST['nom1'];
				} else {
					$action->nom = array($_POST['nom1'], $_POST['nom2'], $_POST['nom3'], $_POST['nom4']);
				}
				break;
			case 'races':
				$str = '';
				if(in_array(1, $valeur)) {
					$str .= '1';
				} else {
					$str .= '0';
				}
				if(in_array(2, $valeur)) {
					$str .= '1';
				} else {
					$str .= '0';
				}		
				if(in_array(3, $valeur)) {
					$str .= '1';
				} else {
					$str .= '0';
				}
				if(in_array(4, $valeur)) {
					$str .= '1';
				} else {
					$str .= '0';
				}				
				$action->races = Actions::raceStrToArray($str);
				break;
			default:
				$action->$cle = $valeur;
		}
	}

	
	if(array_key_exists('effet_type_lanceur',$effets)) {
		$boucle = $effets['effet_type_lanceur'];

		for($k = 0; $k < count($boucle); $k++) {
		
			if($effets['effet_type_lanceur'][$k] != 'nouveau') {
				$type = $effets['effet_type_lanceur'][$k];
				$valeur = $effets['effet_valeur_lanceur'][$k];
				$effet = new Effet($type,$valeur);
				$action->effetsLanceur[] = $effet;
			}
		}
	}
	
	if(array_key_exists('effet_type_cible',$effets)) {	
		$boucle = $effets['effet_type_cible'];

		for($k = 0; $k < count($boucle); $k++) {

			if($effets['effet_type_cible'][$k] != 'nouveau') {
				$type = $effets['effet_type_cible'][$k];
				$valeur = $effets['effet_valeur_cible'][$k];
				$effet = new Effet($type,$valeur);			
				$action->effetsCible[] = $effet;
			}
		}	
	}
		
		
	$resultat = $action->commit();
	
	if(isset($resultat) && @$resultat == true) {
		$titre = "Action";
		$text = "L'action à bien été crée/modifiée.";
		$lien = "../admin/gestion_actions/";
		$root = "../..";
		gestion_erreur($titre, $text, $root, $lien);	
	} else {
		$titre = "Action";
		$text = "Une erreur est survenue. Veuillez vérifier les paramètres de l'action.";
		$lien = "../admin/gestion_actions/";
		$root = "../..";
		gestion_erreur($titre, $text, $root, $lien);	
	}
	
} else {
	$titre = "Action";
	$text = "Une erreur est survenue.";
	$lien = "../admin/gestion_actions/";
	$root = "../..";
	gestion_erreur($titre, $text, $root, $lien);	
}
?>