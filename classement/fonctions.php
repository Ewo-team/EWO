<?php
/**
 * Classement fonctions
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 * @category fonctions
 */

/**
 * Recupération des events des morts
 * @return $resultat
 */
function get_mort_event($idPerso="", $depuisJour="", $nbJours=0, $asc="DESC", $where_race="", $where_grade="", $limit='LIMIT 0,50'){
  //SQL de selection des morts
	$where_perso="";
	if($idPerso!=""){
		$where_perso = " (morgue.id_perso = ".$idPerso." OR morgue.mat_victime = ".$idPerso.") AND ";
		}
	if($asc=="ASC"){
		$asc="ASC";
		}else $asc= "DESC";
		
	$list_idTypeEvent = "8";
	$nbJours*=24*3600;
	if($depuisJour==""){
	$time=date('d-m-Y');
	
	}else{
		$time=$depuisJour;
		}
		
	$Jour=strtotime($time)+24*3600;
	$JourProf=$Jour-$nbJours;

	$Jour = date('Y-m-d H:i:s', $Jour);
	$JourProf = date('Y-m-d H:i:s', $JourProf);
	$sql = "SELECT *
          				FROM morgue 
          					INNER JOIN persos 
          						ON persos.id = morgue.id_perso
          							WHERE ($where_perso	(morgue.date>='$JourProf') AND (morgue.date<'$Jour') $where_race $where_grade)
										ORDER By morgue.date $asc, morgue.id ASC $limit";      

  $resultat = mysql_query ($sql) or die (mysql_error());    
  
  return $resultat;
}  

/**
 * Recupération du compte de mort
 * @return $resultat
 */
function getKillCount($idPerso="", $depuisJour="", $nbJours=0, $asc="DESC", 
						$where_killer_race="", $where_killed_race="", 
						$where_killer_grade="", $where_killed_grade="", 
						$tueur=1){
  //SQL de selection des morts
	$where_perso="";
	if($idPerso!=""){
		if($tueur){
			$where_perso = " (morgue.id_perso = ".$idPerso." AND ";
			}else $where_perso = " (morgue.mat_victime = ".$idPerso." AND ";
		}
	if($asc=="ASC"){
		$asc="ASC";
		}else $asc= "DESC";
		
	$list_idTypeEvent = "8";
	$nbJours*=24*3600;
	if($depuisJour==""){
	$time=date('d-m-Y');
	
	}else{
		$time=$depuisJour;
		}
		
	$Jour=strtotime($time)+24*3600;
	$JourProf=$Jour-$nbJours;

	$Jour = date('Y-m-d H:i:s', $Jour);
	if($nbJours)
		$JourProf = date('Y-m-d H:i:s', $JourProf);
		else $JourProf = '0000-00-00 00:00:00';
	$sql = "SELECT COUNT(morgue.id)
          				FROM morgue 
          					LEFT JOIN persos p1
          						ON p1.id = morgue.id_perso
							LEFT JOIN persos p2
								ON p2.id = morgue.mat_victime
          							WHERE ($where_perso	(morgue.date>='$JourProf') AND (morgue.date<'$Jour') 
											$where_killer_race 
											$where_killed_race
											$where_killer_grade
											$where_killed_grade)
										ORDER By morgue.date $asc, morgue.id ASC";      

  $resultat = mysql_query ($sql) or die (mysql_error());    
  
  return $resultat;
}








?>
