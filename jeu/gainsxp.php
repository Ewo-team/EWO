<?php

include('grille_gainsxp.php');

function gainxp($nbpa,$action,$param = null) {	
	global $gain;
	

	// Calculs
	$coef = 1;
	$modificateur = 0;
	
	if(isset($gain[$action]['param'])) {
		$nom_param = $gain[$action]['param'];
		switch($nom_param) {
			case 'plan':
				$modificateur = ceil(abs($param) / $gain[$action][$nom_param]);
				break;

			case 'esquiver': 
				$modificateur = $param + $gain[$action][$nom_param];
				$coef = 0;
				break;			

			case 'frappe_sort': 
				$modificateur = $param * $gain[$action][$nom_param];
				break;		

			case 'attaque':
				$modificateur = $param * $gain[$action][$nom_param];
				break;

			case 'kill':
				//gain pour le tué
				//param = rang cible - rang tueur
				if($param < 0) { // la cible est de rang inférieure
					$modificateur = ceil(($param * $param)*1.5 + $gain[$action][$nom_param]);
				} else { // la cible est de rang supérieur ou égale !
					$modificateur = $gain[$action]['val'] - $param * $gain[$action][$nom_param];
				}
				$coef = 0;
				break;		

			case 'fullpv':
				$coef = 1;
				$modificateur = 0;
				break;				

			case 'vacance':
				$coef = $param;
				break;
		}	
		
	}
	
	if(isset($gain[$action]['borne_min'])) {
		$calculxp = rand($gain[$action]['borne_min']*$coef+$modificateur,$gain[$action]['borne_max']*$coef+$modificateur);
	} else {
		$calculxp = $gain[$action]['val']*$coef+$modificateur;
	}
	

	if(isset($gain[$action]['cap_min'])) {
		$cap_min = $gain[$action]['cap_min'];
	} else {
		$cap_min = $gain['cap_min']['val'];
	}
	if($calculxp < $cap_min) {
		$calculxp = $cap_min;
	}
	
	if(isset($gain[$action]['cap_max']) && $calculxp > $gain[$action]['cap_max']) {
		$calculxp = $gain[$action]['cap_max'];
	}

	if(isset($gain[$action]['modulation_pa'])) {
		
		$calculxp = ($calculxp / $nbpa) * 2;
		
		$gain_min = floor($calculxp);
		$gain_max = ceil($calculxp);
		$calculxp = rand($gain_min,$gain_max);
	}
	
		
	return $calculxp;
}
