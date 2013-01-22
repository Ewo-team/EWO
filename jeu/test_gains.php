<?php

$root_url = '..';

include('gainsxp.php');

$result['header'][] = 'Nom de PA';

$result['activation1'][] = 'Activation simple';
$result['activation_plan1'][] = 'Activation plan adverse, Y -10';
$result['activation_plan2'][] = 'Activation plan adverse, Y 69';

$result['esquiver_frappe1'][] = 'Esquive frappe, rang égale';
$result['esquiver_frappe2'][] = 'Esquive frappe, rang +5';
$result['esquiver_frappe3'][] = 'Esquive frappe, rang -2';

$result['esquiver_sort_unique1'][] = 'Esquive sort ciblé, rang égale';
$result['esquiver_sort_unique2'][] = 'Esquive sort ciblé, rang +5';
$result['esquiver_sort_unique3'][] = 'Esquive sort ciblé, rang -2';

$result['esquiver_sort_zone1'][] = 'Esquive sort zone, rang égale';
$result['esquiver_sort_zone2'][] = 'Esquive sort zone, rang +5';
$result['esquiver_sort_zone3'][] = 'Esquive sort zone, rang -2';

$result['sort_rate'][] = 'Sort raté';

$result['sort_unique_t31'][] = 'Sort ciblé, T3, rang égale';
$result['sort_unique_t32'][] = 'Sort ciblé, T3, rang +5';
$result['sort_unique_t33'][] = 'Sort ciblé, T3, rang -2';

$result['sort_unique_t71'][] = 'Sort ciblé, T7, rang égale';
$result['sort_unique_t72'][] = 'Sort ciblé, T7, rang +5';
$result['sort_unique_t73'][] = 'Sort ciblé, T7, rang -2';

$result['sort_unique_esquive_t31'][] = 'Sort ciblé esquivé, T3, rang égale';
$result['sort_unique_esquive_t32'][] = 'Sort ciblé esquivé, T3, rang +5';
$result['sort_unique_esquive_t33'][] = 'Sort ciblé esquivé, T3, rang -2';

$result['sort_unique_esquive_t71'][] = 'Sort ciblé esquivé, T7, rang égale';
$result['sort_unique_esquive_t72'][] = 'Sort ciblé esquivé, T7, rang +5';
$result['sort_unique_esquive_t73'][] = 'Sort ciblé esquivé, T7, rang -2';

$result['sort_zone_t31'][] = 'Sort de zone, T3, rang égale';
$result['sort_zone_t32'][] = 'Sort de zone, T3, rang +5';
$result['sort_zone_t33'][] = 'Sort de zone, T3, rang -2';

$result['sort_zone_t71'][] = 'Sort de zone, T7, rang egale';
$result['sort_zone_t72'][] = 'Sort de zone, T7, rang +5';
$result['sort_zone_t73'][] = 'Sort de zone, T7, rang -2';	

$result['sort_surlanceur_reussi'][] = 'Sort sur lanceur réussi';
$result['sort_surlanceur_esquive'][] = 'Sort sur lanceur esquivé';

$result['attaque_t31'][] = 'Attaque d\'un T3, rang égale';
$result['attaque_t32'][] = 'Attaque d\'un T3, rang +5';
$result['attaque_t33'][] = 'Attaque d\'un T3, rang -2';	

$result['tueur_t31'][] = 'Tue un T3 par frappe, rang égale';
$result['tueur_t32'][] = 'Tue un T3 par frappe, rang +5';
$result['tueur_t33'][] = 'Tue un T3 par frappe, rang -2';	

$result['tueur_sort1'][] = 'Tue 1 perso par sort de zone';
$result['tueur_sort2'][] = 'Tue 2 perso par sort de zone';
$result['tueur_sort3'][] = 'Tue 3 perso par sort de zone';	
$result['tueur_sort4'][] = 'Tue 4 perso par sort de zone';	

$result['frappe_famille1'][] = 'Frappe ou tue un membre de sa famille (T7), rang égale';
$result['frappe_famille2'][] = 'Frappe ou tue un membre de sa famille (T7), rang +5';
$result['frappe_famille3'][] = 'Frappe ou tue un membre de sa famille (T7), rang -2';

$result['tue_t31'][] = 'Tué par un T3, rang égale';
$result['tue_t32'][] = 'Tué par un T3, rang +5';
$result['tue_t33'][] = 'Tué par un T3, rang -2';	

$result['tue_t71'][] = 'Tué par un T7, rang égale';
$result['tue_t72'][] = 'Tué par un T7, rang +5';
$result['tue_t73'][] = 'Tue par un T7, rang -2';

$result['repare_batiment1'][] = 'Répare un batiment endommagé';
$result['repare_batiment2'][] = 'Répare un batiment full-pv';

	$result['suicide1'][] = 'Premier suicide';
	$result['suicide2'][] = '2ème suicide';
	$result['suicide3'][] = '3ème suicide';
	$result['suicide4'][] = '4ème suicide';
	$result['suicide5'][] = '5ème suicide';
	$result['suicide6'][] = '6ème suicide';
	
	$result['vacance1'][] = '1 jour de vacance';
	$result['vacance2'][] = '2 jour de vacance';
	$result['vacance3'][] = '4 jour de vacance';
	$result['vacance4'][] = '8 jour de vacance';
	$result['vacance5'][] = '16 jour de vacance';
	
$result['attaque_recu1'][] = 'Attaque recu, rang égale';
$result['attaque_recu2'][] = 'Attaque recu, rang +5';
$result['attaque_recu3'][] = 'Attaque recu, T3, rang -2';	

$result['sprint'][] = 'Sprint';


for($pa = 20; $pa <= 40; $pa++){
	$nbpa = $pa / 10;
	
	$result['header'][] = $nbpa;

	$result['activation1'][] = gainxp($nbpa,'activation');
	$result['activation_plan1'][] = gainxp($nbpa,'activation_plan', -10);
	$result['activation_plan2'][] = gainxp($nbpa,'activation_plan', 69);
	
	$result['esquiver_frappe1'][] = gainxp($nbpa,'esquiver_frappe', 0);
	$result['esquiver_frappe2'][] = gainxp($nbpa,'esquiver_frappe', +5);
	$result['esquiver_frappe3'][] = gainxp($nbpa,'esquiver_frappe', -2);

	$result['esquiver_sort_unique1'][] = gainxp($nbpa,'esquiver_sort_unique', 0);
	$result['esquiver_sort_unique2'][] = gainxp($nbpa,'esquiver_sort_unique', +5);
	$result['esquiver_sort_unique3'][] = gainxp($nbpa,'esquiver_sort_unique', -2);

	$result['esquiver_sort_zone1'][] = gainxp($nbpa,'esquiver_sort_zone', 0);
	$result['esquiver_sort_zone2'][] = gainxp($nbpa,'esquiver_sort_zone', +5);
	$result['esquiver_sort_zone3'][] = gainxp($nbpa,'esquiver_sort_zone', -2);
	
	$result['sort_rate'][] = gainxp($nbpa,'sort_rate');

	$result['sort_unique_t31'][] = gainxp($nbpa,'sort_unique_t3', 0);
	$result['sort_unique_t32'][] = gainxp($nbpa,'sort_unique_t3', +5);
	$result['sort_unique_t33'][] = gainxp($nbpa,'sort_unique_t3', -2);	

	$result['sort_unique_t71'][] = gainxp($nbpa,'sort_unique_t7', 0);
	$result['sort_unique_t72'][] = gainxp($nbpa,'sort_unique_t7', +5);
	$result['sort_unique_t73'][] = gainxp($nbpa,'sort_unique_t7', -2);	
	
	$result['sort_unique_esquive_t31'][] = gainxp($nbpa,'sort_unique_esquive_t3', 0);
	$result['sort_unique_esquive_t32'][] = gainxp($nbpa,'sort_unique_esquive_t3', +5);
	$result['sort_unique_esquive_t33'][] = gainxp($nbpa,'sort_unique_esquive_t3', -2);	

	$result['sort_unique_esquive_t71'][] = gainxp($nbpa,'sort_unique_esquive_t7', 0);
	$result['sort_unique_esquive_t72'][] = gainxp($nbpa,'sort_unique_esquive_t7', +5);
	$result['sort_unique_esquive_t73'][] = gainxp($nbpa,'sort_unique_esquive_t7', -2);		
	
	$result['sort_zone_t31'][] = gainxp($nbpa,'sort_zone_t3', 0);
	$result['sort_zone_t32'][] = gainxp($nbpa,'sort_zone_t3', +5);
	$result['sort_zone_t33'][] = gainxp($nbpa,'sort_zone_t3', -2);		

	$result['sort_zone_t71'][] = gainxp($nbpa,'sort_zone_t7', 0);
	$result['sort_zone_t72'][] = gainxp($nbpa,'sort_zone_t7', +5);
	$result['sort_zone_t73'][] = gainxp($nbpa,'sort_zone_t7', -2);		
	
	$result['sort_surlanceur_reussi'][] = gainxp($nbpa,'sort_surlanceur_reussi');	
	$result['sort_surlanceur_esquive'][] = gainxp($nbpa,'sort_surlanceur_esquive');	
	
	$result['attaque_t31'][] = gainxp($nbpa,'attaque_t3', 0);
	$result['attaque_t32'][] = gainxp($nbpa,'attaque_t3', +5);
	$result['attaque_t33'][] = gainxp($nbpa,'attaque_t3', -2);	

	$result['tueur_t31'][] = gainxp($nbpa,'tueur_t3', 0);
	$result['tueur_t32'][] = gainxp($nbpa,'tueur_t3', +5);
	$result['tueur_t33'][] = gainxp($nbpa,'tueur_t3', -2);	
	
	$result['tueur_sort1'][] = gainxp($nbpa,'tueur_sort', 1);
	$result['tueur_sort2'][] = gainxp($nbpa,'tueur_sort', 2);
	$result['tueur_sort3'][] = gainxp($nbpa,'tueur_sort', 3);	
	$result['tueur_sort4'][] = gainxp($nbpa,'tueur_sort', 4);	

	$result['frappe_famille1'][] = gainxp($nbpa,'frappe_famille', 0);
	$result['frappe_famille2'][] = gainxp($nbpa,'frappe_famille', +5);
	$result['frappe_famille3'][] = gainxp($nbpa,'frappe_famille', -2);		
	
	$result['tue_t31'][] = gainxp($nbpa,'tue_t3', 0);
	$result['tue_t32'][] = gainxp($nbpa,'tue_t3', +5);
	$result['tue_t33'][] = gainxp($nbpa,'tue_t3', -2);		
	
	$result['tue_t71'][] = gainxp($nbpa,'tue_t7', 0);
	$result['tue_t72'][] = gainxp($nbpa,'tue_t7', +5);
	$result['tue_t73'][] = gainxp($nbpa,'tue_t7', -2);	
	
	
	$result['repare_batiment1'][] = gainxp($nbpa,'repare_batiment', false);	
	$result['repare_batiment2'][] = gainxp($nbpa,'repare_batiment', true);	
	
	$result['suicide1'][] = gainxp($nbpa,'suicide', 0);	
	$result['suicide2'][] = gainxp($nbpa,'suicide', 1);	
	$result['suicide3'][] = gainxp($nbpa,'suicide', 2);	
	$result['suicide4'][] = gainxp($nbpa,'suicide', 3);	
	$result['suicide5'][] = gainxp($nbpa,'suicide', 4);	
	$result['suicide6'][] = gainxp($nbpa,'suicide', 5);	
	
	$result['vacance1'][] = gainxp($nbpa,'vacance', 1);	
	$result['vacance2'][] = gainxp($nbpa,'vacance', 2);	
	$result['vacance3'][] = gainxp($nbpa,'vacance', 4);	
	$result['vacance4'][] = gainxp($nbpa,'vacance', 8);	
	$result['vacance5'][] = gainxp($nbpa,'vacance', 16);		
	
	$result['attaque_recu1'][] = gainxp($nbpa,'attaque_recu', 0);
	$result['attaque_recu2'][] = gainxp($nbpa,'attaque_recu', +5);
	$result['attaque_recu3'][] = gainxp($nbpa,'attaque_recu', -2);		
	//-----
	$result['sprint'][] = gainxp($nbpa,'sprint');	
	
}
echo '<table border="1">';
foreach($result as $lignes) {
	echo '<tr>';
	foreach($lignes as $cases) {
		echo "<td>$cases</td>";
	}
	echo '</tr>';
}
/*
echo gainxp(2,'attaque_recu', 0);
echo gainxp(2,'attaque_recu', +5);
echo gainxp(2,'attaque_recu', -2);	

echo '<hr>';
*/
/*$valeurs = array(15,
				19,19,19,19,19,
				20,20,20,20,20,20,
				21,21,21,21,21,
				23,23,23,23,
				24,24,24,24,24,24,24,24,24,
				25,25,25,
				26,26
				);*/
/*
$valeurs = array(3426,2375,4379,18328,4237,22492,4974);
$valeurs2 = array(3426,2375,-10000,4379,18328,4237,22492,4974);
				
echo moyenneStable($valeurs, 0);
echo moyenneStable($valeurs2, 0);

echo '<hr>';

$perso_inc = 12;
$perso_id = array(125,127,514,515,516,919,1026,817,818,819,666,119,1000);
sort($perso_id);
$cible_id1 = 125;
$cible_id2 = 1000;
$nbiter = 100000;

$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	
	$nb_perso=$perso_inc;
	for($inci=1; $inci<=$nb_perso; $inci++){
		if($cible_id1==$perso_id[$inci]){
			$famille=true;
		}
	}
}	
$temps_fin = microtime(true);
echo '<br>Boucle, 12 perso, on cherche le 1er : '.round($temps_fin - $temps_debut, 4);

$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	
	$nb_perso=$perso_inc;
	for($inci=1; $inci<=$nb_perso; $inci++){
		if($cible_id2==$perso_id[$inci]){
			$famille=true;
		}
	}
}	
$temps_fin = microtime(true);
echo '<br>Boucle, 12 perso, on cherche le 12ème : '.round($temps_fin - $temps_debut, 4);

$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	if(array_search($cible_id1, $perso_id)) {
		$famille=true;
	}
}	
$temps_fin = microtime(true);
echo '<br>Array_search, 12 perso, on cherche le 1er : '.round($temps_fin - $temps_debut, 4);

$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	if(array_search($cible_id2, $perso_id)) {
		$famille=true;
	}
}	
$temps_fin = microtime(true);
echo '<br>Array_search, 12 perso, on cherche le 12ème : '.round($temps_fin - $temps_debut, 4);


$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	if(in_array($cible_id1, $perso_id)) {
		$famille=true;
	}
}	
$temps_fin = microtime(true);
echo '<br>in_array, 12 perso, on cherche le 1er : '.round($temps_fin - $temps_debut, 4);

$temps_debut = microtime(true);
for($iteration=0; $iteration <=$nbiter; $iteration++) {
	if(in_array($cible_id2, $perso_id)) {
		$famille=true;
	}
}	
$temps_fin = microtime(true);
echo '<br>in_array, 12 perso, on cherche le 12ème : '.round($temps_fin - $temps_debut, 4);*/