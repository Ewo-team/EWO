<?php

/**
 * Met à jour le marqueur d'esquive magique
 * @param type type de records, chaine de caractère
 * @param perso_id id du perso concerné
 * @param valeur valeur a mettre sous forme de tableau sérialisé
 */

include_once(SERVER_ROOT . '/persos/medailles.php');

function maj_esq_mag($perso_id, $maj, $force_raz){
$sql = "SELECT date_tour, date_esquivemagique FROM persos WHERE id = '".$perso_id."'";
$resultat = mysql_query ($sql) or die (mysql_error());
$persos = mysql_fetch_array ($resultat);

$datetour = $persos['date_tour'];
$datetour = strtotime($datetour);
$datechangement = strtotime($persos['date_esquivemagique']);

$time = time();

$plan = 0; // Valeur par défaut
$nv_tour = 23; // Valeur par défaut
$sql="SELECT * FROM damier_persos
		INNER join cartes ON cartes.id=damier_persos.carte_id
			WHERE perso_id='$perso_id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	if($pos = mysql_fetch_array ($resultat)){
		$plan = $pos['carte_id'];
		$nv_tour = $pos['dla'];
		}
if($maj==2){
		$sql = "SELECT maj_esq_mag FROM caracs WHERE perso_id=$perso_id";
		$res = mysql_query($sql)or die (mysql_error());
		$res = mysql_fetch_array($res);
		if($res['maj_esq_mag']==1 || $res['maj_esq_mag']==2)
			$val=2;
			else $val=0;
		$sql = "UPDATE caracs SET maj_esq_mag=$val WHERE perso_id=$perso_id";
		$res = mysql_query($sql) or die (mysql_error());
		}

if($maj && $time >= ($datechangement + 3600*12)){
	if($maj==2){
		$sql = "SELECT maj_esq_mag FROM caracs WHERE perso_id=$perso_id";
		$res = mysql_query($sql)or die (mysql_error());
		$res = mysql_fetch_array($res);
		if($res['maj_esq_mag']==1 || $res['maj_esq_mag']==2)
			$val=2;
			else $val=0;
		$sql = "UPDATE caracs SET maj_esq_mag=$val WHERE perso_id=$perso_id";
		}else  {
			$sql = "UPDATE persos SET date_esquivemagique = NOW( ) WHERE id=$perso_id";
			$res = mysql_query($sql)or die (mysql_error());

			$sql = "SELECT maj_esq_mag FROM caracs WHERE perso_id=$perso_id";
			$res = mysql_query($sql)or die (mysql_error());
			$res = mysql_fetch_array($res);
			if($res['maj_esq_mag']==2)
				$val=3;
				else $val='not(maj_esq_mag)';
			$sql = "UPDATE caracs SET maj_esq_mag=$val WHERE perso_id=$perso_id";
			}
	$res = mysql_query($sql) or die (mysql_error());
	}

if($force_raz){
	$sql = "UPDATE caracs SET maj_esq_mag=0 WHERE perso_id=$perso_id";
	$res = mysql_query($sql) or die (mysql_error());
	}
}


//Calcul de rang
function calcul_rang($xp){
	if($xp>0)
		$rang = floor((-3+sqrt(9+2.5*$xp/25))/2);
		else $rang = floor(($xp-5)/5);
	return $rang;
}

function ajuste_rang($grade){
	//Un chef ail? a 2 rang de plus
	if($grade==5){
	return 2;
	}
	//G4 ail? a 1 rang de plus
	if($grade==4){
	return 1;
	}
return 0;
}
// Fonction permettant de récupérer une à plusieurs caracs
function recup_carac($perso_id, $carac_tab){

if(!isset($perso_id) || !isset($carac_tab) ||count($carac_tab)==0){
    return false;
    }else{
    $inc=1;
    $requete=$carac_tab[0];
    while(isset($carac_tab[$inc])){
    $requete=$requete.', '.$carac_tab[$inc];
    $inc++;
    }
    $sql = "SELECT $requete FROM caracs WHERE perso_id=$perso_id";
    $res = mysql_query($sql)or die (mysql_error());
    return mysql_fetch_array ($res);
    }
}

function calcul_caracs_no_alter($perso_id){
return recup_carac($perso_id, array('*'));
}

function recup_alter_spawn($perso_id){

$sql = "SELECT alter_spawn
                FROM persos
                    WHERE id = $perso_id";

$reponse = mysql_query($sql) or die (mysql_error());
$spawn = mysql_fetch_array($reponse);
return $spawn['alter_spawn'];
}


/**
 * Rcupère le type de jeu associé à la race
 * @param race id de la race dont on cherche le type de jeu
 */

// function recup_type($race){
// if($race!=0){
	// $sql = "SELECT type
					// FROM races
						// WHERE race_id = $race";

	// $reponse = mysql_query($sql) or die (mysql_error());
	// $reponse = mysql_fetch_array($reponse);
	// return $reponse['type'];
	// }else return 3;
// }


/**
 * Rcupère le camp associé à la race
 * @param race id de la race dont on cherche le camp
 */

function recup_camp($race){
if($race!=0){
	$sql = "SELECT camp_id
					FROM races
						WHERE race_id = $race";

	$reponse = mysql_query($sql) or die (mysql_error());
	$reponse = mysql_fetch_array($reponse);
	return $reponse['camp_id'];
	}else return 0;
}

/**
 * Rcupère le plan associé au camp
 * @param camp id du camp dont on cherche le plan d'origine
 */

function recup_camp_plan($camp){
if($camp!=0){
	$sql = "SELECT carte_id
					FROM camps
						WHERE id = $camp";

	$reponse = mysql_query($sql) or die (mysql_error());
	$reponse = mysql_fetch_array($reponse);
	return $reponse['carte_id'];
	}else return 1;
}

function recup_race_grade($perso_id){

$sql = "SELECT persos.race_id, persos.grade_id, persos.galon_id, races.type
                FROM persos INNER JOIN races ON (persos.race_id = races.race_id AND persos.grade_id = races.grade_id)
                    WHERE persos.id = $perso_id";

$reponse = mysql_query($sql) or die (mysql_error());
return mysql_fetch_array($reponse);
}

function recup_affil($perso_id){

$sql = "SELECT superieur_id AS sup
                FROM persos
                    WHERE id = $perso_id";

$reponse = mysql_query($sql) or die (mysql_error());
$reponse = mysql_fetch_array($reponse);
return $reponse['sup'];
}

// Fonction permettant de récupérer une à plusieurs caracs
function recup_carac_alter_mag($perso_id, $carac){

    $sql = "SELECT SUM($carac) FROM caracs_alter_mag WHERE perso_id=$perso_id GROUP BY perso_id";
    $res = mysql_query($sql)or die (mysql_error());
	return mysql_fetch_array ($res);

}

// Fonction permettant de mettre à jour une carac
function maj_carac($perso_id, $carac, $new_value){
	if(is_numeric($new_value)){
	$sql = "UPDATE caracs SET `$carac`=$new_value WHERE perso_id=$perso_id";
	$res = mysql_query($sql)or die (mysql_error());
	}
}
// Fonction permettant de mettre à jour une carac
function maj_alter_spawn($perso_id, $new_value){
	if(is_numeric($new_value)){
	$sql = "UPDATE persos SET `alter_spawn`=$new_value WHERE id=$perso_id";
	$res = mysql_query($sql)or die (mysql_error());
	}
}

// Fonction permettant de mettre à jour une carac
function maj_carac_alter_mag($perso_id, $carac, $new_value){
	if(is_numeric($new_value)){
	$sql = "INSERT INTO caracs_alter_mag (perso_id, $carac) VALUE ($perso_id, $new_value)";
	$res = mysql_query($sql)or die (mysql_error());
	}
}

//Fonction remettant à zero les bonus de Plan.
function raz_alter_plan($perso_id){
$sql="SELECT * FROM caracs_alter_plan WHERE perso_id=$perso_id";
$res = mysql_query($sql)or die (mysql_error());
if(!mysql_fetch_row($res)){
		$sql_carac_alter = mysql_query("INSERT INTO `caracs_alter_plan` (`perso_id`,
																`alter_pa`,
																`alter_pv`,
																`alter_mouv`,
																`alter_def`,
																`alter_att`,
																`alter_recup_pv`,
																`alter_force`,
																`alter_perception`,
																`alter_niv_mag`,
																`alter_res_mag`,
																`alter_effet`)
														VALUES ('$perso_id',
																0,
																0,
																0,
																0,
																0,
																0,
																0,
																0,
																0,
																0,
																0)
									") or die (mysql_error());
	}
	else {
		mysql_query("UPDATE `caracs_alter_plan` SET 	`alter_pa`=0,
														`alter_pv`=0,
														`alter_mouv`=0,
														`alter_def`=0,
														`alter_att`=0,
														`alter_recup_pv`=0,
														`alter_force`=0,
														`alter_perception`=0,
														`alter_niv_mag`=0,
														`alter_res_mag`=0,
														`alter_effet`=0
									WHERE perso_id=$perso_id") or die (mysql_error());
		}
}

// Fonction permettant de mettre à jour une carac
function maj_alter_plan($perso_id, $carac, $new_value){
	if(is_numeric($new_value)){
	$sql = "UPDATE caracs_alter_plan SET $carac=$new_value WHERE perso_id=$perso_id";
	$res = mysql_query($sql)or die (mysql_error());
	}
}


/**
 * Renvoie un tableau de cout de chaque carac en fonction de la race et du grade
 * @param race id de la race
 * @param grade id du grade
 */

function cout_caracs_base($race, $grade){

$type = recup_type($race);

switch($type){
//type 4 cases : familles
	case 4 :
		switch($grade){
			case 4 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>120,"des"=>80,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			case 5 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>120,"des"=>60,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			default :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>120,"des"=>100,"force"=>80,"perception"=>140, "magie"=>100);
			}
		break;
// type 0 : parias
	case 0 :
		switch($grade){
			case 4 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>80,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			case 5 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>60,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			default :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>100,"force"=>80,"perception"=>140, "magie"=>100);
			}
		break;
// type 3 cases : initialement ailés
	default :
		switch($grade){
			case 4 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>80,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			case 5 :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>60,"force"=>80,"perception"=>140, "magie"=>100);
				break;
			default :
				$cout = array ("pv"=>80, "recup_pv"=>150,"mouv"=>140,"pa"=>100,"des"=>100,"force"=>80,"perception"=>140, "magie"=>100);
				}

	}
return $cout;
}

// Fonction renvoyant les Caracs de base dans un array suivant la race et le grade
function caracs_base ($race, $grade){

$type = recup_type($race);

switch($type){
	case 7 :
		//-- Humain
		/*
		$pv = 80;
		$recup_pv = 10;
		$mouv = 6;
		$pa = 2;
		$des_max = 7;
		$force = 10;
		$perception = 4;
		*/
		if ($grade == -1)
			$caracs = array ("pa"=>1, "pv"=>1, "recup_pv"=>1,"mouv"=>1,"pa"=>1,"des"=>1,"force"=>1,"perception"=>1, "magie"=>0, "res_mag"=>0);
		if ($grade >=0 && $grade < 4)
			$caracs = array ("pa"=>2, "pv"=>80, "recup_pv"=>20,"mouv"=>6,"pa"=>2,"des"=>7,"force"=>10,"perception"=>4, "magie"=>0, "res_mag"=>0);
		if ($grade == 4)
			$caracs = array ("pv"=>120, "recup_pv"=>20,"mouv"=>6,"pa"=>2.3,"des"=>9,"force"=>15,"perception"=>4, "magie"=>0, "res_mag"=>0);
		if ($grade  == 5)
			$caracs = array ("pv"=>200, "recup_pv"=>20,"mouv"=>6,"pa"=>2.6,"des"=>11,"force"=>20,"perception"=>4, "magie"=>0, "res_mag"=>0);
		break;
	case 4 :
		if ($grade == -1)
			$caracs = array ("pv"=>1, "recup_pv"=>1,"mouv"=>1,"pa"=>1,"des"=>1,"force"=>1,"perception"=>1, "magie"=>0, "res_mag"=>0);
		if ($grade >=0 && $grade < 4)
			$caracs = array ("pv"=>100, "recup_pv"=>10,"mouv"=>6,"pa"=>1.5,"des"=>9,"force"=>10,"perception"=>5, "magie"=>0, "res_mag"=>0);
		if ($grade == 4)
			$caracs = array ("pv"=>150, "recup_pv"=>10,"mouv"=>6,"pa"=>1.8,"des"=>11,"force"=>13,"perception"=>5, "magie"=>0, "res_mag"=>0);
		if ($grade  == 5)
			$caracs = array ("pv"=>300, "recup_pv"=>10,"mouv"=>6,"pa"=>2.1,"des"=>13,"force"=>14,"perception"=>5, "magie"=>0, "res_mag"=>0);
		break;
	case 0 :
		// Paria
		if ($grade == -1)
			$caracs = array ("pa"=>1, "pv"=>1, "recup_pv"=>1,"mouv"=>1,"pa"=>1,"des"=>1,"force"=>1,"perception"=>1, "magie"=>0, "res_mag"=>0);
		if ($grade >=0)
			$caracs = array ("pa"=>2, "pv"=>160, "recup_pv"=>5,"mouv"=>6,"pa"=>2,"des"=>8,"force"=>20,"perception"=>5, "magie"=>0, "res_mag"=>0);
		break;
	case 3 :
		//-- Ange/Demon grade 0
		/*
		$pv = 200;
		$recup_pv = 20;
		$mouv = 6;
		$pa = 2;
		$des_max = 9;
		$force = 20;
		$perception = 5;
		*/
		if ($grade == -1)
			$caracs = array ("pa"=>1, "pv"=>1, "recup_pv"=>1,"mouv"=>1,"pa"=>1,"des"=>1,"force"=>1,"perception"=>1, "magie"=>0, "res_mag"=>0);
		if ($grade >=0)
			$caracs = array ("pa"=>2, "pv"=>200, "recup_pv"=>5,"mouv"=>6,"pa"=>2,"des"=>9,"force"=>20,"perception"=>5, "magie"=>0, "res_mag"=>0);
		if ($grade == 4)
			$caracs = array ("pv"=>350, "recup_pv"=>5,"mouv"=>6,"pa"=>2.1,"des"=>11,"force"=>25,"perception"=>6, "magie"=>0, "res_mag"=>0);
		if ($grade == 5)
			$caracs = array ("pv"=>500, "recup_pv"=>5,"mouv"=>7,"pa"=>2.2,"des"=>13,"force"=>28,"perception"=>6, "magie"=>0, "res_mag"=>0);
		break;
	default :
		if ($grade == -1)
			$caracs = array ("pa"=>1, "pv"=>1, "recup_pv"=>1,"mouv"=>1,"pa"=>1,"des"=>1,"force"=>1,"perception"=>1, "magie"=>0, "res_mag"=>0);
		if ($grade >=0)
			$caracs = array ("pa"=>2, "pv"=>200, "recup_pv"=>5,"mouv"=>6,"pa"=>2,"des"=>9,"force"=>20,"perception"=>5, "magie"=>0, "res_mag"=>0);
		}
return $caracs;
}


//Fonction de calcul de recup des malus en dé en fonction de la recup pv et de la race
function recup_malus($recup_pv, $pv_max){

$res = 0;
$recup_malus 	= ((20+2*$recup_pv)*$pv_max/100)/10;
$recup_arr		= floor($recup_malus);
$bonus			= round(($recup_malus - $recup_arr)*100);
$test 			= rand(1,100);
if($test<$bonus){
	$res = 1;
	}

return array("chance" => $bonus, "recup_fixe"=>$recup_arr, "recup_bonus"=>$res);
}

function bonus_galon($carac, $race, $grade, $galon){
$bonus=0;

// Ailés
$bonus_['pv'][3][2][2]=10;
$bonus_['pv'][3][2][3]=20;
$bonus_['pv'][3][2][4]=30;

$bonus_['pv'][3][3][1]=30;
$bonus_['pv'][3][3][2]=40;
$bonus_['pv'][3][3][3]=50;
$bonus_['pv'][3][3][4]=60;

$bonus_['pv'][3][4][1]=0;
$bonus_['pv'][3][4][2]=10;
$bonus_['pv'][3][4][3]=20;
$bonus_['pv'][3][4][4]=30;

$bonus_['pv'][3][5][1]=0;
$bonus_['pv'][3][5][2]=20;
$bonus_['pv'][3][5][3]=60;
$bonus_['pv'][3][5][4]=100;

$bonus_['pv'][4]=$bonus_['pv'][3];

// Humains
$bonus_['pv'][1][2][2]=4;
$bonus_['pv'][1][2][3]=8;
$bonus_['pv'][1][2][4]=12;

$bonus_['pv'][1][3][1]=12;
$bonus_['pv'][1][3][2]=16;
$bonus_['pv'][1][3][3]=20;
$bonus_['pv'][1][3][4]=24;

$bonus_['pv'][1][4][1]=0;
$bonus_['pv'][1][4][2]=6;
$bonus_['pv'][1][4][3]=12;
$bonus_['pv'][1][4][4]=18;

$bonus_['pv'][1][5][1]=0;
$bonus_['pv'][1][5][2]=10;
$bonus_['pv'][1][5][3]=20;
$bonus_['pv'][1][5][4]=40;

// Parias identique
$bonus_['pv'][2]=$bonus_['pv'][3];

$bonus_['force'][3][2][2]=1;
$bonus_['force'][3][2][3]=2;
$bonus_['force'][3][2][4]=3;

$bonus_['force'][3][3][1]=3;
$bonus_['force'][3][3][2]=4;
$bonus_['force'][3][3][3]=5;
$bonus_['force'][3][3][4]=6;

$bonus_['force'][3][4][1]=0;
$bonus_['force'][3][4][2]=0;
$bonus_['force'][3][4][3]=1;
$bonus_['force'][3][4][4]=2;

$bonus_['force'][3][5][1]=0;
$bonus_['force'][3][5][2]=1;
$bonus_['force'][3][5][3]=2;
$bonus_['force'][3][5][4]=3;

$bonus_['force'][4]=$bonus_['force'][3];

// Humains
$bonus_['force'][1][2][2]=1;
$bonus_['force'][1][2][3]=1;
$bonus_['force'][1][2][4]=2;

$bonus_['force'][1][3][1]=2;
$bonus_['force'][1][3][2]=2;
$bonus_['force'][1][3][3]=3;
$bonus_['force'][1][3][4]=3;

$bonus_['force'][1][4][1]=0;
$bonus_['force'][1][4][2]=0;
$bonus_['force'][1][4][3]=0;
$bonus_['force'][1][4][4]=1;

$bonus_['force'][1][5][1]=0;
$bonus_['force'][1][5][2]=1;
$bonus_['force'][1][5][3]=1;
$bonus_['force'][1][5][4]=2;

$bonus_['force'][2]=$bonus_['force'][3];

// Ailés
$bonus_['pa'][3][2][2]=1;
$bonus_['pa'][3][2][3]=1;
$bonus_['pa'][3][2][4]=1;

$bonus_['pa'][3][3][1]=1;
$bonus_['pa'][3][3][2]=1;
$bonus_['pa'][3][3][3]=1;
$bonus_['pa'][3][3][4]=1;

$bonus_['pa'][3][4][1]=0;
$bonus_['pa'][3][4][2]=0;
$bonus_['pa'][3][4][3]=0;
$bonus_['pa'][3][4][4]=1;

$bonus_['pa'][3][5][1]=0;
$bonus_['pa'][3][5][2]=0;
$bonus_['pa'][3][5][3]=0;
$bonus_['pa'][3][5][4]=1;

$bonus_['pa'][4]=$bonus_['pa'][3];

$bonus_['pa'][1]=$bonus_['pa'][3];
$bonus_['pa'][2]=$bonus_['pa'][3];

// Ailés
$bonus_['des'][3][2][2]=0;
$bonus_['des'][3][2][3]=0;
$bonus_['des'][3][2][4]=1;

$bonus_['des'][3][3][1]=1;
$bonus_['des'][3][3][2]=1;
$bonus_['des'][3][3][3]=1;
$bonus_['des'][3][3][4]=2;

$bonus_['des'][3][4][1]=0;
$bonus_['des'][3][4][2]=1;
$bonus_['des'][3][4][3]=1;
$bonus_['des'][3][4][4]=2;

$bonus_['des'][3][5][1]=0;
$bonus_['des'][3][5][2]=1;
$bonus_['des'][3][5][3]=1;
$bonus_['des'][3][5][4]=2;

$bonus_['des'][4]=$bonus_['des'][3];

// Humains
$bonus_['des'][1]=$bonus_['des'][3];

$bonus_['des'][2]=$bonus_['des'][3];

// Ailés
$bonus_['res_mag'][3][2][1]=0;
$bonus_['res_mag'][3][2][2]=0;
$bonus_['res_mag'][3][2][3]=0;
$bonus_['res_mag'][3][2][4]=5;

$bonus_['res_mag'][3][3][1]=0;
$bonus_['res_mag'][3][3][2]=5;
$bonus_['res_mag'][3][3][3]=10;
$bonus_['res_mag'][3][3][4]=10;

$bonus_['res_mag'][3][4][1]=10;
$bonus_['res_mag'][3][4][2]=15;
$bonus_['res_mag'][3][4][3]=15;
$bonus_['res_mag'][3][4][4]=20;

$bonus_['res_mag'][3][5][1]=15;
$bonus_['res_mag'][3][5][2]=20;
$bonus_['res_mag'][3][5][3]=20;
$bonus_['res_mag'][3][5][4]=25;

$bonus_['res_mag'][4]=$bonus_['res_mag'][3];

$bonus_['res_mag'][1]=$bonus_['res_mag'][3];
$bonus_['res_mag'][2]=$bonus_['res_mag'][3];
// En cours de refonte

if(isset($bonus_[$carac][$race][$grade][$galon]))
	$bonus=$bonus_[$carac][$race][$grade][$galon];

return $bonus;
}

// Fonction renvoyant la valeur max d'une carac suivant la race, le grade, le galon, le niveau de celle ci
// Exemple carac_max (1, 0, 'pv', 2) renvoie 96, soit le nombre de PV max d'un humain de grade 0 et ayant mis deux points en pv.
function carac_max ($race, $grade, $carac, $niv, $perso_id, $galon = null){
$caracs_base = caracs_base ($race, $grade);
$new_caracs_base = $caracs_base;

if(!isset($galon)) {
	$sql_galon="SELECT `galon_id` AS galon
			FROM `persos`
				WHERE `id`=$perso_id";
	$reponse = mysql_query($sql_galon) or die (mysql_error());
	$galon = mysql_fetch_array($reponse);
	$galon = $galon['galon'];
}

if ($grade == -1){
if ($carac =='magie'){
    $new_caracs_base[$carac] = 0;
    }else $new_caracs_base[$carac] = 1;
}else{
$new_caracs_base['pv']        	= round($caracs_base['pv'] + $caracs_base['pv']*$niv/10);
$new_caracs_base['force']       = round($caracs_base['force'] + $caracs_base['force']*$niv/10);
$new_caracs_base['des']         = round($caracs_base['des'] + $niv);
$new_caracs_base['recup_pv']    = round($caracs_base['recup_pv'] + 5*$niv);
$new_caracs_base['mouv']        = round($caracs_base['mouv'] + $niv);
//$new_caracs_base['pa_dec']      = ($caracs_base['pa']*10)%10 + $niv/10;
//$new_caracs_base['pa']          = floor($caracs_base['pa']) + $niv/10;
$new_caracs_base['perception']  = round($caracs_base['perception'] + $niv);
$new_caracs_base['magie']       = round($caracs_base['magie'] + $niv);

//Application des bonus de galons
$new_caracs_base['pv']     	  	+= bonus_galon($carac, $race, $grade, $galon);
$new_caracs_base['force']    	+= bonus_galon($carac, $race, $grade, $galon);
$new_caracs_base['des']      	+= bonus_galon($carac, $race, $grade, $galon);
$new_caracs_base['recup_pv'] 	+= $new_caracs_base['recup_pv']*bonus_galon($carac, $race, $grade, $galon)/100;
$new_caracs_base['mouv']    	+= bonus_galon($carac, $race, $grade, $galon);

$pa_galon = $caracs_base['pa'] + bonus_galon('pa', $race, $grade, $galon)/10 + $niv/10;

//if( round(10*$pa_galon)%10 == 0 && round(10*$pa_galon)/10>$new_caracs_base['pa'])
//$new_caracs_base['pa'] = round(10*$pa_galon)/10;
//$new_caracs_base['pa_dec'] 		= round(10*$pa_galon)%10;
$new_caracs_base['pa_dec']      = ($pa_galon*10)%10;
$new_caracs_base['pa']          = floor($pa_galon);

$new_caracs_base['perception']	+= bonus_galon($carac, $race, $grade, $galon);
$new_caracs_base['magie']     	+= bonus_galon($carac, $race, $grade, $galon);
$new_caracs_base['res_mag']     += bonus_galon($carac, $race, $grade, $galon);
}
//ajouter les bonus de galons.

//echo "- $carac : ".$new_caracs_base[$carac]."<br>";
return $new_caracs_base[$carac];
}

// Fonction renvoyant la valeur max d'une carac suivant la race, le grade, le niveau de celle ci
// Exemple carac_max (1, 0, 'pv', 2) renvoie 96, soit le nombre de PV max d'un humain de grade 0 et ayant mis deux points en pv.
function carac_max_no_galon ($race, $grade, $carac, $niv, $perso_id=''){
$caracs_base = caracs_base ($race, $grade);
$new_caracs_base = $caracs_base;

if ($grade == -1){
if ($carac =='magie'){
    $new_caracs_base[$carac] = 0;
    }else $new_caracs_base[$carac] = 1;
}else{
$new_caracs_base['pv']        	= round($caracs_base['pv'] + $caracs_base['pv']*$niv/10);
$new_caracs_base['force']       = round($caracs_base['force'] + $caracs_base['force']*$niv/10);
$new_caracs_base['des']         = round($caracs_base['des'] + $niv);
$new_caracs_base['recup_pv']    = round($caracs_base['recup_pv'] + 5*$niv);
$new_caracs_base['mouv']        = round($caracs_base['mouv'] + $niv);
$new_caracs_base['pa_dec']      = $niv-$niv%10;
$new_caracs_base['pa']          = $caracs_base['pa'] + $niv/10;
$new_caracs_base['perception']  = round($caracs_base['perception'] + $niv);
$new_caracs_base['magie']       = round($caracs_base['magie'] + $niv);

}
//ajouter les bonus de galons.

return $new_caracs_base[$carac];
}


// Fonction calculant les caracs de base pondérées par le niveau du joueur dans celle-ci
function caracs_base_max ($id_perso, $race, $grade){

$sql_caracs="SELECT `pi`,`niv_pv`, `niv_recup_pv`, `niv_mouv`, `niv_pa`, `niv_des` AS `niv_des`,`niv_force`,`niv_perception`,`niv` AS `magie`
                FROM `caracs`
                    WHERE `perso_id` = $id_perso";
$reponse = mysql_query($sql_caracs) or die (mysql_error());
$niv = mysql_fetch_array($reponse);

$galon 		= recup_race_grade($id_perso);
$old_race  	= $galon['race_id'];
$old_grade 	= $galon['grade_id'];
$galon 		= $galon['galon_id'];

if($old_grade!=$grade || $race!=$old_race){
	if($old_grade> 3 || $grade>3 || $race!=$old_race){
		$cout		= cout_caracs_base($race, $grade);
		$old_cout 	= cout_caracs_base($old_race, $old_grade);
		$cout_base		= $cout['des'];
		$old_cout_base 	= $old_cout['des'];
		$go_niv=0;
		$pi_dep = 0;
		while($go_niv!=$niv['niv_des']){
			$go_niv++;
			$pi_dep += $old_cout['des'];
			$old_cout['des']+=$old_cout_base/10;
			}
		$new_niv=0;
		$pi_to_dep=0;
		while($pi_dep>$pi_to_dep){
			$new_niv++;
			$pi_to_dep += $cout['des'];
			$cout['des']+=$cout_base/10;
			}
		if($pi_dep<$pi_to_dep){

			$diff = $pi_to_dep-$pi_dep;
			if($cout['des']/2<$diff){
				$new_niv--;
				$pi_to_dep -= ($cout['des']-$cout_base/10);
				$diff = $pi_to_dep-$pi_dep;
				}
			$new_pi = $niv['pi']-$diff;
			mysql_query("UPDATE caracs SET  `pi`=$new_pi
										 WHERE perso_id = $id_perso") or die (mysql_error());
			}
		$niv['niv_des']= $new_niv;
		mysql_query("UPDATE caracs SET  `niv_des`=$new_niv
                                     WHERE perso_id = $id_perso") or die (mysql_error());
		}
	}

$max_ = caracs_base ($race, $grade);

$max['pv'] = carac_max ($race, $grade, 'pv', $niv['niv_pv'], $id_perso);
$max['pa'] = carac_max ($race, $grade, 'pa', $niv['niv_pa'], $id_perso);
$max['pa_dec'] = carac_max ($race, $grade, 'pa_dec', $niv['niv_pa'], $id_perso);
$max['recup_pv'] = carac_max ($race, $grade, 'recup_pv', $niv['niv_recup_pv'], $id_perso);
$max['mouv'] = carac_max ($race, $grade, 'mouv', $niv['niv_mouv'], $id_perso);
$max['des'] = carac_max ($race, $grade, 'des', $niv['niv_des'], $id_perso);
$max['force'] = carac_max ($race, $grade, 'force', $niv['niv_force'], $id_perso);
$max['perception'] = carac_max ($race, $grade, 'perception', $niv['niv_perception'], $id_perso);
$max['magie'] = carac_max ($race, $grade, 'magie', $niv['magie'], $id_perso);
$max['res_mag'] = carac_max ($race, $grade, 'res_mag', 0, $id_perso);
return $max;
}


// Fonction calculant les nouvelles caractéristiques lors d'un changement de grade ou de race
// Exemple : renew_caracs($id_perso, 3, 5) un perso qui devient Archange
// Ses pv initiaux était de 150/200 ils deviendront de 450/600
function renew_caracs($id_perso, $new_race, $new_grade){
$sql = "SELECT race_id, grade_id
                FROM persos
                    WHERE id = $id_perso";

$reponse = mysql_query($sql) or die (mysql_error());
$old_caracs = mysql_fetch_array($reponse);
$old_race =$old_caracs['race_id'];
$old_grade=$old_caracs['grade_id'];

$sql_caracs="SELECT `pv`, `recup_pv`, `mouv`, `pa`, `des_attaque` AS `des`,`force`,`perception`,`niv` AS `magie`
                FROM `caracs`
                    WHERE `perso_id` = $id_perso";
$reponse = mysql_query($sql_caracs) or die (mysql_error());
$old_caracs = mysql_fetch_array($reponse);

$old_max = caracs_base_max ($id_perso, $old_race, $old_grade);
$new_max = caracs_base_max ($id_perso, $new_race, $new_grade);

if ($new_grade == -1){
$new_caracs = $old_caracs;
$new_caracs['pv']            = 1;
$new_caracs['recup_pv']        = 1;
$new_caracs['mouv']            = 1;
$new_caracs['pa']            = 1;
$new_caracs['des']            = 1;
$new_caracs['force']        = 1;
$new_caracs['perception']    = 1;
$new_caracs['magie']        = 0;
}else{
$new_caracs = $old_caracs;
$new_caracs['pv']            = round($old_caracs['pv']*$new_max['pv']/$old_max['pv']);
$new_caracs['recup_pv']        = round($old_caracs['recup_pv']*$new_max['recup_pv']/$old_max['recup_pv']);
$new_caracs['mouv']            = round($old_caracs['mouv']*$new_max['mouv']/$old_max['mouv']);
$new_caracs['pa']            = round($old_caracs['pa']*$new_max['pa']/$old_max['pa'], 1);
$new_caracs['des']            = round($old_caracs['des']*$new_max['des']/$old_max['des']);
$new_caracs['force']        = round($old_caracs['force']*$new_max['force']/$old_max['force']);
$new_caracs['perception']    = round($old_caracs['perception']*$new_max['perception']/$old_max['perception']);
$new_caracs['magie']        = round($old_caracs['magie']);
}

mysql_query("UPDATE caracs SET  pv = ".$new_caracs['pv'].",
                             pa = ".$new_caracs['pa'].",
                             mouv = ".$new_caracs['mouv'].",
                             recup_pv = ".$new_caracs['recup_pv'].",
                             niv = ".$new_caracs['magie'].",
                             des_attaque = ".$new_caracs['des'].",
                             `force` = ".$new_caracs['force'].",
                             perception = ".$new_caracs['perception']."
                                     WHERE perso_id = $id_perso") or die (mysql_error());
}


function desaffil_all($perso_id){
mysql_query("UPDATE persos SET superieur_id = 0
					WHERE superieur_id = $perso_id") or die (mysql_error());
	}


// Fonction de changement de grade et/ou race. Il faut lui donner l'id du perso, le nouveau grade et la nouvelle race
function change_race_grade($id_perso, $new_race, $new_grade){
	$sql = "SELECT race_id, grade_id
					FROM persos
						WHERE id = $id_perso";

	$reponse = mysql_query($sql) or die (mysql_error());
	$old_caracs = mysql_fetch_array($reponse);
	$old_race =$old_caracs['race_id'];
	$old_grade=$old_caracs['grade_id'];

	if($new_grade > $old_grade) {
		// Si le gradé passe G4
		if($new_grade == 4) {
			ajouteMedaille(MEDAILLE_G4, $id_perso);
		}

		// Si le gradé passe G4
		if($new_grade == 5) {
			ajouteMedaille(MEDAILLE_G5, $id_perso);
		}
	}

	if($old_grade==5){
		desaffil_all($id_perso);
	}
	
	if($old_grade != $new_grade) {
		$em = new persos\eventManager\eventManager();
		$ev1 = $em->createEvent('grade');
		$ev1->setSource($id_perso, 'perso');
		$ev1->infos->addPublicInfo('i',$old_grade);
		$ev1->infos->addPublicInfo('f',$new_grade);	
	}
	
	renew_caracs($id_perso, $new_race, $new_grade);

	mysql_query("UPDATE persos SET grade_id = $new_grade
					WHERE id = $id_perso") or die (mysql_error());
	mysql_query("UPDATE persos SET race_id = $new_race
					WHERE id = $id_perso") or die (mysql_error());

	}

function change_galon($perso_id,$galon){
mysql_query("UPDATE persos SET galon_id = $galon
				WHERE id = $perso_id") or die (mysql_error());
}

function bonus_effet($perso_race, $perso_grade, $perso_galon){
/*
$type = recup_type($perso_race);

switch($perso_race){
	case 1 :
		$effet= 0;
		break;
	// case 2 :
		// $effet=110;
		// break;
	default :*/
		switch($perso_grade){
			case -1 :
				$effet = 0;
				break;
			case 0 :
			case 1 :
			case 2 :
				$effet = 0;
				break;
			case 3 :
				$effet = 10;
				break;
			case 4 :
				$effet = 25;
				break;
			case 5 :
				$effet = 40;
				break;
			}/*
	}*/
return $effet;
}

function grade_up_xp($perso_id,$race,$grade,$xp){

	if(($grade == 0 && $xp > 500) || ($grade == 1 && $xp > 1000)){
		if($grade == 0 && $xp > 1000) {
			change_race_grade($perso_id, $race, 2);
		} else {
			change_race_grade($perso_id, $race, $grade + 1);
		}

		$ok=0;
		for($inci=1; $inci<=$_SESSION['persos']['inc']; $inci++){
			if($_SESSION['persos']['id'][$inci]==$perso_id){
				$ok=1;
				$id=$inci;
			}
		}
		if($ok){
			$_SESSION['persos']['grade'][$id]+=1;
		}
    }
}

function grade_kill($perso_id, $cible_id, $perso_race, $cible_race, $perso_type, $cible_type, $perso_grade, $cible_grade, $perso_galon, $cible_galon) {

	$difference = $cible_grade - $perso_grade;

	if($difference >= 1) {
		if($perso_type == 7 || $cible_type == 7) {
			$gain = 15;
		} else {
			$gain = 35;
		}

		// laisse 1% de chance de gain si on tue un G4/G5
		if($cible_grade >= 4) {
			$gain = 1;
		}

		$perte = 50;

		switch($cible_grade) {
			case 3: $perte -= 5; break;
			case 4: $perte -= 10; break;
			case 5: $perte -= 15; break;
		}

		switch($cible_galon) {
			case 2: $perte -= 10; break;
			case 3: $perte -= 20; break;
			case 4: $perte -= 30; break;
		}


		if($gain>=lance_ndp(1,100)){
			change_race_grade($perso_id, $perso_race, $perso_grade + 1);
			change_galon($perso_id,max(1, $perso_galon-2));

			$id	= $_SESSION['persos']['id'][0];
			$_SESSION['persos']['grade'][$id] = $perso_grade + 1;
		}

		// Test cible

		if($perte>=lance_ndp(1,100)){
			change_race_grade($cible_id, $cible_race, $cible_grade - 1);
			change_galon($cible_id,$cible_galon);
		}
	}

}

function select_caracs_alter_mag($perso_id) {
	$sql="SELECT perso_id,
            SUM(alter_pa) as alter_pa,
            SUM(alter_pv) as alter_pv,
            SUM(alter_mouv) as alter_mouv,
            SUM(alter_def) as alter_def,
            SUM(alter_att) as alter_att,
            SUM(alter_recup_pv) as alter_recup_pv,
            SUM(alter_force) as alter_force,
            SUM(alter_perception) as alter_perception,
            SUM(alter_niv_mag) as alter_niv_mag,
            SUM(alter_effet) as alter_effet,
            SUM(immunite) as immunite,
            SUM(alter_res_mag) as alter_res_mag,
            SUM(alter_esq_mag) as alter_esq_mag,
            SUM(alter_res_phy) as alter_res_phy
            FROM caracs_alter_mag WHERE perso_id='$perso_id' GROUP BY perso_id";
        //$sql="SELECT * FROM caracs_alter_mag WHERE perso_id='$perso_id'";    
	$resultat = mysql_query ($sql) or die (mysql_error());
	return mysql_fetch_array ($resultat);    
}
//Recuper la somme des alterations par carac
function calcul_caracs_alter($perso_id=''){

	if($perso_id==''){
	$perso_id = $_SESSION['persos']['current_id'];
	}

	$caracs_alter_mag = select_caracs_alter_mag($perso_id);

        
	$sql="SELECT * FROM caracs_alter_plan WHERE perso_id='$perso_id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$carac_alter_plan = mysql_fetch_array ($resultat);

	$sql="SELECT * FROM caracs_alter WHERE perso_id='$perso_id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$carac_alter = mysql_fetch_array ($resultat);

	$sql="SELECT * FROM caracs_alter_affi WHERE perso_id='$perso_id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$carac_alter_affi = mysql_fetch_array ($resultat);

        error_reporting(0);
	foreach($carac_alter_affi as $key => $value){
		$carac_alter[$key] += $value;
	}

	$sql="SELECT * FROM caracs_alter_artefact
            INNER JOIN inventaire ON perso_id='$perso_id'
                    WHERE caracs_alter_artefact.case_artefact_id=inventaire.case_artefact_id";
	$resultat = mysql_query ($sql) or die (mysql_error());

	$nb=0;
	while ($res_carac_alter_artefact = mysql_fetch_array ($resultat)){
	$nb++;
	$carac_alter_artefact[$nb]=$res_carac_alter_artefact;
	}
	$carac_alter_artefact[0]=$nb;


	// PA
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_pa'] +=$carac_alter_artefact[$inci]['alter_pa'];
		}
	$caracs_alter_mag['alter_pa'] +=$carac_alter['alter_pa'] + $carac_alter_plan['alter_pa'];

	// PV
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_pv'] +=$carac_alter_artefact[$inci]['alter_pv'];
		}
	$caracs_alter_mag['alter_pv'] +=$carac_alter['alter_pv'] + $carac_alter_plan['alter_pv'];

	// Mouv
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_mouv'] +=$carac_alter_artefact[$inci]['alter_mouv'];
		}
	$caracs_alter_mag['alter_mouv'] +=$carac_alter['alter_mouv'] + $carac_alter_plan['alter_mouv'];

	// Def
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_def'] +=$carac_alter_artefact[$inci]['alter_def'];
		}
	$caracs_alter_mag['alter_def'] +=$carac_alter['alter_def'] + $carac_alter_plan['alter_def'];

	// Att
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_att'] +=$carac_alter_artefact[$inci]['alter_att'];
		}
	$caracs_alter_mag['alter_att'] +=$carac_alter['alter_att'] + $carac_alter_plan['alter_att'];

	//Recup
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_recup_pv'] +=$carac_alter_artefact[$inci]['alter_recup_pv'];
		}
	$caracs_alter_mag['alter_recup_pv'] +=$carac_alter['alter_recup_pv'] + $carac_alter_plan['alter_recup_pv'];

	//Force
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_force'] +=$carac_alter_artefact[$inci]['alter_force'];
		}
	$caracs_alter_mag['alter_force'] +=$carac_alter['alter_force'] + $carac_alter_plan['alter_force'];

	//Perception
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_perception'] +=$carac_alter_artefact[$inci]['alter_perception'];
		}
	$caracs_alter_mag['alter_perception'] +=$carac_alter['alter_perception'] + $carac_alter_plan['alter_perception'];

	//Niveau de magie
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['alter_niv_mag'] +=$carac_alter_artefact[$inci]['alter_niv_mag'];
		}
	$caracs_alter_mag['alter_niv_mag'] +=$carac_alter['alter_niv_mag'] + $carac_alter_plan['alter_niv_mag'];

	//Immunité
	for($inci=1; $inci<=$nb;$inci++){
		$caracs_alter_mag['immunite'] +=$carac_alter_artefact[$inci]['immunite'];
		}
	//Alteration des effets
	for($inci=1; $inci<=$nb;$inci++){
               $caracs_alter_mag['alter_effet'] +=$carac_alter_artefact[$inci]['alter_effet'];
               }
	$caracs_alter_mag['alter_effet'] +=$carac_alter['alter_effet'] + $carac_alter_plan['alter_effet'];

	//Alteration de la resistance magique
	for($inci=1; $inci<=$nb;$inci++){
               $caracs_alter_mag['alter_res_mag'] +=$carac_alter_artefact[$inci]['alter_res_mag'];
               }
	$caracs_alter_mag['alter_res_mag'] +=$carac_alter['alter_res_mag'] + $carac_alter_plan['alter_res_mag'];

	//Alteration de la resistance physique
	for($inci=1; $inci<=$nb;$inci++){
               $caracs_alter_mag['alter_esq_mag'] +=$carac_alter_artefact[$inci]['alter_esq_mag'];
               }
	$caracs_alter_mag['alter_esq_mag'] +=$carac_alter['alter_esq_mag'] + $carac_alter_plan['alter_esq_mag'];

	return $caracs_alter_mag;
	}


//Calcul la valeur courante des caracs, donc avec alteration
function calcul_caracs($perso_id=''){

if($perso_id==''){
$perso_id = $_SESSION['persos']['current_id'];
}

$race_grade = recup_race_grade($perso_id);
$niv_force = recup_carac($perso_id, array('niv_force'));
$force = carac_max ($race_grade['race_id'], $race_grade['grade_id'], 'force', $niv_force['niv_force'], $perso_id);

$carac_alter = calcul_caracs_alter($perso_id);

$caracs = calcul_caracs_no_alter($perso_id);

// PA
$caracs["pa"] +=$carac_alter['alter_pa'];

// PV
$caracs["pv"] +=$carac_alter['alter_pv'];

// Mouv
$caracs["mouv"] +=$carac_alter['alter_mouv'];

// Def
$caracs["def"]=0;
$caracs["def"] +=$carac_alter['alter_def'];

// Att
$caracs["att"]=0;
$caracs["att"] +=$carac_alter['alter_att'];

//Recup
$caracs["recup_pv"] +=$carac_alter['alter_recup_pv'];

//Force
$caracs["force"] +=floor($force*$carac_alter['alter_force']/100);

//Niveau de magie
$caracs["niv"] +=$carac_alter['alter_niv_mag'];
$caracs["magie"]=$caracs["niv"];

//Niveau de magie
$caracs["perception"] +=$carac_alter['alter_perception'];
$caracs["perception"] = max($caracs["perception"], 1);

// Immunite
$caracs["immunite"]=0;
$caracs["immunite"] +=$carac_alter['immunite'];

// Resistance magique
$caracs["res_mag"] +=$carac_alter['alter_res_mag'];

// Esquive magique
$caracs["esq_mag"] +=$carac_alter['alter_esq_mag'];

return $caracs;
}


function poid_portable($id_perso){
/*
Humain : Force + Force x grade
Ailé : Force + Force x grade
Paria : Force x 1,5
*/

	$sql = "SELECT race_id, grade_id FROM persos WHERE id = '".$id_perso."'";
	$repon = mysql_query($sql) or die (mysql_error());
	$perso = mysql_fetch_array($repon);

	$sql1 = "SELECT * FROM caracs	WHERE perso_id = '".$id_perso."'";
	$reponse = mysql_query($sql1) or die (mysql_error());
	$force = mysql_fetch_array($reponse);

	if($perso['race_id'] == 1){
		$poid = $force['force'] + ($force['force'] * $perso['grade_id']);
	}elseif($perso['race_id'] == 3){
		$poid = $force['force'] + ($force['force'] * $perso['grade_id']);
	}elseif($perso['race_id'] == 4){
		$poid = $force['force'] + ($force['force'] * $perso['grade_id']);
	}elseif($perso['race_id'] == 2){
		$poid = $force['force'] * 1.5;
	}elseif($perso['race_id'] > 4){
		$poid = $force['force'] * 1.5;
	}
	return $poid;
}

function poid_courant($perso_id){
	$inventaires = "SELECT sum(A.poid) as poid
										FROM inventaire I
											JOIN case_artefact A
												ON I.case_artefact_id = A.id
													WHERE I.perso_id = '".$perso_id."'";
	$resultat = mysql_query ($inventaires) or die (mysql_error());
	$objet = mysql_fetch_array ($resultat);
	$poid = $objet['poid'];
	if (empty($poid)){
		$poid = '0';
	}
	return $poid;
}

function raz_all(){
// mysql_query("UPDATE persos SET date_tour = '0000-00-00 00:00:00' WHERE id >=0") or die (mysql_error());
// mysql_query("UPDATE persos SET grade_id = '0', galon_id = '0', nb_suicide = '0' WHERE grade_id <5") or die (mysql_error());
// mysql_query("TRUNCATE TABLE `record`") or die (mysql_error());

// $sql="UPDATE `caracs` SET 	`des_attaque`=1,
							// `malus_def`=0,
							// `cercle`=0,
							// `pa_dec`=0,
							// `px`=0,
							// `pi`=0,
							// `niv_pv`=0,
							// `niv_pa`=0,
							// `niv_mouv`=0,
							// `niv_des`=0,
							// `niv_recup_pv`=0,
							// `niv_force`=0,
							// `niv_perception`=0,
							// `niv`=0
							// WHERE perso_id>=0";
// $resultat = mysql_query ($sql) or die (mysql_error());

// $sql="UPDATE `caracs_alter` SET `alter_pa`=0,
								// `alter_mouv`=0,
								// `alter_def`=0,
								// `alter_att`=0,
								// `alter_recup_pv`=0,
								// `alter_force`=0,
								// `alter_perception`=0,
								// `nb_desaffil`=0,
								// `alter_niv_mag`=0
								// WHERE perso_id>=0";
// $resultat = mysql_query ($sql) or die (mysql_error());

// $sql="UPDATE `caracs_alter_mag` SET `alter_pa`=0,
								// `alter_mouv`=0,
								// `alter_def`=0,
								// `alter_att`=0,
								// `alter_recup_pv`=0,
								// `alter_force`=0,
								// `alter_perception`=0,
								// `alter_niv_mag`=0
								// WHERE perso_id>=0";
// $resultat = mysql_query ($sql) or die (mysql_error());

// $sql="UPDATE `caracs_alter_plan` SET `alter_pa`=0,
								// `alter_mouv`=0,
								// `alter_def`=0,
								// `alter_att`=0,
								// `alter_recup_pv`=0,
								// `alter_force`=0,
								// `alter_perception`=0,
								// `alter_niv_mag`=0
								// WHERE perso_id>=0";
// $resultat = mysql_query ($sql) or die (mysql_error());

// $sql="DELETE FROM `ewo`.`damier_persos` WHERE `damier_persos`.`carte_id` > 1";

// $resultat = mysql_query ($sql) or die (mysql_error());
}



/**
* Retourne une ligne du tableau de la liste des persos
**/

function lignePerso($perso,$carac,$inc){

	$id			= $perso['id_perso'];
	$caracs		= calcul_caracs($id);

	$nom		= nom_perso($perso['id_perso']);
	$race		= $perso['race_id'];
	$grade		= $perso['grade_id'];
	$couleur	= $perso['couleur'];
	$date_tour	= $perso['tour'];
        
        $mortel         = $perso['mortel'];
 
	$x			= $carac['pos_x'];
	$y			= $carac['pos_y'];
	$plan		= $carac['cartes'];
       
	foreach ( $caracs as $k => $v ) {
		$$k		= $v;
	}


	$pa_max		= carac_max ($race, $grade, 'pa', $niv_pa, $id) + carac_max ($race, $grade, 'pa_dec', $niv_pa, $id)/10;
	$pv_max		= carac_max ($race, $grade, 'pv', $niv_pv, $id);

	$recup_malus = recup_malus($recup_pv, $pv_max);
	$recup_malus = $recup_malus["recup_fixe"];

	$mouv_max	= carac_max ($race, $grade, 'mouv', $niv_mouv, $id);
	$force_max	= carac_max($race,$grade,'force',$niv_force,$id);
	$des_max	= carac_max($race,$grade,'des',$niv_des,$id);
	$recup_pv_max =carac_max($race,$grade,'recup_pv',$niv_recup_pv,$id);
	$perception_max	= carac_max($race,$grade,'perception',$niv_perception,$id);

	$recup_malus_max = recup_malus(carac_max($race,$grade,'recup_pv',$niv_recup_pv,$id), $pv_max);
	$recup_malus_max = $recup_malus_max["recup_fixe"];

	$url		= icone_persos($id);

	// Calcule si l'activation du nouveau tour peu etre faite ou pas
	if('0000-00-00 00:00:00' != $date_tour){
		$timestamp	= strtotime($date_tour);
		$date_tour	= date('d/m/y',$timestamp).'<br />'.date('H:i:s',$timestamp);
	}
	else{
            $date_tour = 'Jouer';
 	}

	$time		= time();

	$retour		= array();
        
        if($mortel >= 1) {
            $retour[]	= '<tr class="mortel">';
        } else {
            $retour[]	= '<tr>';
        }
	//Icone
	$retour[]	= '<td class="tab_td_icone"><img src="'.SERVER_URL.'/images/'.$url.'" alt="avatar" title="Avatar de '.$nom.'" /></td>';
	//Nom et matricule (avec lien sur la page d'évènement
	$retour[]	= '<td class="tab_td_nom"><a href="'.SERVER_URL.'/persos/event/?id='.$id.'">'.$nom.' ('.$id.')</a> <a href="'.SERVER_URL.'/persos/editer_perso.php?id='.$id.'"><img src="'.SERVER_URL.'/images/site/reply.png"</a></td>';
	//Points de vie (restants et total)
	$retour[]	= persoCaracCase($pv,'fonce',$pv_max,1);
	//Mouvements (restants et total)
	$retour[]	= persoCaracCase($mouv,'clair',$mouv_max);
	//Points d'actions
	$pa_restants= $pa+$pa_dec/10;
	$retour[]	= persoCaracCase($pa_restants,'fonce',$pa_max);
	//Force
	$retour[]	= persoCaracCase($force,'clair',$force_max,1);
	//Dextérité
	$des_def = $des_max - $des_attaque;
	if('0' != $def){
		if($def<0){
			$alter_def = ' ('.$def.')';
		}
		else{
			$alter_def = ' (+'.$def.')';
		}
	}
	else{
		$alter_def = '';
	}
	if('0' != $att){
		if($att<0){
			$alter_att = ' ('.$att.')';
		}
		else{
			$alter_att = ' (+'.$att.')';
		}
	}
	else{
		$alter_att = '';
	}
	$retour[]	= persoCaracCase($des_attaque,'fonce',false,false,$alter_att,'',1,'Att. ').'<br />';
	$retour[]	= persoCaracCase($des_def,'fonce',false,false,$alter_def,'',2,'Def. ');
	//Malus défensif
	$retour[]	= persoCaracCase($malus_def,'clair');
	//Magie
	$retour[]	= persoCaracCase($magie,'fonce',false,false,'','',1,'Niv. ').'<br />';
	//Résistence magique
	$retour[]	= persoCaracCase($res_mag,'fonce',false,3,'%','',2,'Res. ');
	//Recup PV
	$recup_pv_display = round($pv_max * $recup_pv /100,0);
	$recup_pv_max_display = round($pv_max * $recup_pv_max /100,0);
	$retour[]	= persoCaracCase($recup_pv_display,'clair',$recup_pv_max_display,false,'','',1).' <br /> ';
	//Recup malus
	$recup_malus_display = $recup_malus;
	$recup_malus_max_display = $recup_malus_max;
	$retour[]	= persoCaracCase($recup_malus_display,'clair',$recup_malus_max_display,false,'','',2);
	//Perception
	$retour[]	= persoCaracCase($perception,'fonce',$perception_max);
	//XP et PI
	$retour[]	= persoCaracCase($pi,'clair',false,false,'','',1).'/';
	$retour[]	= persoCaracCase($px,'clair',false,false,'','',2);
	//Position
	if (isset($x) && isset($y)){
		$retour[]= '<td class="fonce">X: '.$x.' <br /> Y: '.$y.' <br /> ('.$plan.')</td>';
	}
	else{
		$retour[]= '<td class="fonce">-</td>';
                if($mortel == 1) {
                    $date_tour = 'Mort';
                }
	}
	//Prochain tour
	if(!isset($timestamp) || $timestamp < $time){
		$class='red_text';
	}
	else{
		$class = '';
	}
	$retour[]	= '<td class="tab_td_tour"><a class="'.$class.'" href="'.SERVER_URL.'/jeu/index.php?perso_id='.$inc.'">'.$date_tour.'</a></td>';
	$retour[]	= '</tr>';
	return join(PHP_EOL,$retour);
}

function persoCaracCase($valeur, $classe, $valeur_max = false, $color = false,$suffixe1='', $suffixe2='',$tags=false,$prefixe = ''){
	$class= '';

	switch($color){
		case '1' :
			if($valeur<$valeur_max*0.25){
				$class = ' class="red_text" ';
			}
			break;
		case '2' :
			if($valeur>0){
				$class = ' class="red_text" ';
			}
			break;
		case '3' :
			if($valeur<0){
				$class = ' class="red_text" ';
			}
			break;
		default :
			$class = '';
	}

	$retour = '';
	if($tags != 2){
		$retour = '<td class="'.$classe.'">';
	}

	$retour.=$prefixe;

	if(false === $valeur_max){
		$retour .= $valeur.$suffixe1;
	}
	else{
		if($valeur>$valeur_max && $color!='4'){
			$class = ' class="green_text" ';
		}
		$retour .= '<span '.$class.'>'.$valeur.$suffixe1.'/'.$valeur_max.$suffixe2.'</span>';
	}

	if($tags != 1){
		$retour .= '</td>';
	}

	return $retour;
}
?>
