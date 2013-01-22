<?php
	if(!isset($admin_mode)){
		$coutPvBase      = 80;
		$coutRecupPvBase = 150;
		$coutMouvBase    = 140;
		$coutForceBase   = 80;
		$coutNvMagBase   = 100;
			$addNvMag    = 200;//Valeur à ajouter au cout précédent
		$coutPercBase    = 140;
		
	}

	switch ($grade){
		case 4: // Si c'est un grade 4
			$coutDesBase = 80; 
			break;
		case 5: // Si c'est un grade 5
			$coutDesBase = 60;
			break;
		default: // Si le perso a un grade inférieur à 4
			$coutDesBase = 100;
			break;
	}
	
	switch(recup_type($race)) {
		case 4:
			$coutPaBase      = 120;
			break;
		default:	
			$coutPaBase      = 100;
			break;		
	}

	
	function calculeSommeXp($val,$nombreup,$augmentation = null) {
		//echo "$val $nombreup";
		if(!$augmentation) {
			$augmentation = $val/10;
		}
		$up = (($nombreup-1) * ($nombreup)) / 2;
		return $up * $augmentation + (($nombreup)*$val);
	}
?>
