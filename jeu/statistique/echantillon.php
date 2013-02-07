<?php

/**
* Fonction sql retournant la liste a parser
*/
function sql_stat($date_debut, $date_fin, $champs){
$date_debut = $date_debut - 100;
$date_fin = $date_fin + 100;
$persos = "SELECT ".$champs." FROM stat_popvivante WHERE date BETWEEN '".$date_debut."' AND '".$date_fin."'";
$resultat = mysql_query ($persos) or die(mysql_error());
	while($nbperso = mysql_fetch_array ($resultat)){
		$liste[] = $nbperso[$champs];
	}
	return $liste;
}

/**
* Tableau de moyenne
*/
function moyenne($n_rotation, $liste){
	$b=0;
	for($i=0;$i<$n_rotation;$i++){
		for($a=0;$a<4;$a++){
			@$rotation[$i] += $liste[$b];
			$b++;
		}
	}

	for($i=0;$i<$n_rotation;$i++){
		$moy_rotation[] = round($rotation[$i]/4, 0);
	}
	
	return $moy_rotation;
}

/**
* Création de la var inline a mettre dans le js
*/
function var_inline($array,$guil=0, $multi=0){
	if($multi==0){
		$liste = '';
		foreach($array as $value){
			if($guil==1){
				$liste .= "'".$value."',";
			}else{
				$liste .= $value.',';
			}
		}
		$liste = substr($liste, 0, -1);
	}else{
		$liste = '';
		foreach($array as $key => $value){
			$liste .= "{name: '".$key."',	data: [";
			foreach($array[$key] as $valeur){
				$liste .= $valeur.",";
			}
			$liste = substr($liste, 0, -1);
			$liste .= "]},";
		}
		$liste = substr($liste, 0, -1);
	}
		return $liste;
}

/**
* Création de la boucle inline des dates
*/
function date_inline($ret_date_debut,$temps){
	$b = 0;
	for($i=1;$i<=$temps;$i++){
		
		$date_inline[$i] = date("d-M-Y",$ret_date_debut+$b);
		$b += 86400;
	}
	
	return $date_inline;
}


/**
*
*/
function liste_personnage($date_debut, $date_fin, $rq){
	$temps = round(($date_fin- $date_debut)/24/60/60);

	$ange = sql_stat($date_debut, $date_fin, 'ange_'.$rq);
	$demon = sql_stat($date_debut, $date_fin, 'demon_'.$rq);
	$humain = sql_stat($date_debut, $date_fin, 'humain_'.$rq);

	$moy_ange = moyenne($temps,$ange);
	$moy_demon = moyenne($temps,$demon);
	$moy_humain = moyenne($temps,$humain);
	
	if($temps == 1){
		$list = array ('ange'=>$ange, 'demon'=>$demon, 'humain'=>$humain);
		$inline_y = "'00:00','06:00','12:00','18:00'";
		$inline_x = var_inline($list,0,1);
	}else{
		$list_moy = array ('ange'=>$moy_ange, 'demon'=>$moy_demon, 'humain'=>$moy_humain);
		$array = date_inline($date_debut,$temps);
		$inline_y = var_inline($array,1,0);
		$inline_x = var_inline($list_moy,0,1);	
	}
		
	return array ($inline_y, $inline_x);

	//return $list_moy;
}

/**
* Liste du nombre d'utilisateur
*/
function liste_utilisateur($ret_date_debut, $ret_date_fin){
	$temps = round(($ret_date_fin-$ret_date_debut)/24/60/60);
	$liste = sql_stat($ret_date_debut, $ret_date_fin, 'nb_joueur_total');
	
	if($temps == 1){
		$inline_y = "'00:00','06:00','12:00','18:00'";
		$inline_x = var_inline($liste,0,0);
	}else{
		$array = date_inline($ret_date_debut,$temps);
		$inline_y = var_inline($array,1,0);
		$inline_x = var_inline(moyenne($temps, $liste),0,0);	
	}
		
	return array ($inline_y, $inline_x);
}

?>
