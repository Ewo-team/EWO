<?php
$image_loc = "./../images/";

$sql="SELECT races.camp_id AS camp, races.nom AS nom_grade, persos.id AS id, persos.nom AS nom, persos.nb_suicide AS nb_suicide, persos.race_id AS race_id, persos.nom_race AS nom_race, persos.grade_id AS grade_id, persos.superieur_id AS superieur_id, persos.faction_id AS faction_id, persos.date_tour AS date_tour
				FROM persos 
					INNER JOIN races
						ON races.race_id=persos.race_id AND races.grade_id=persos.grade_id
					WHERE persos.utilisateur_id = '".$id_utilisateur."' ORDER BY persos.id ASC";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$inc=1;
		$_SESSION['persos']['inc'] = 0;
		while($persos = mysql_fetch_array ($resultat)){
			
			$_SESSION['persos']['inc']					= $inc;
			$_SESSION['persos']['id'][$inc] 			= $persos['id'];
			$_SESSION['persos']['nom'][$inc] 			= $persos['nom'];
			$_SESSION['persos']['nb_suicide'][$inc] 	= $persos['nb_suicide'];
			$_SESSION['persos']['race'][$inc] 			= $persos['race_id'];
			$_SESSION['persos']['camp'][$inc] 			= $persos['camp'];
			$_SESSION['persos']['grade'][$inc] 			= $persos['grade_id'];
			$_SESSION['persos']['grade']['nom'][$inc] 	= $persos['nom_grade'];
			$_SESSION['persos']['faction']['id'][$inc]	= $persos['faction_id'];
			$_SESSION['persos']['date_tour'][$inc]	    = $persos['date_tour'];
                        $_SESSION['persos']['nom_race'][$inc]       = $persos['nom_race'];
			
                        $_SESSION['persos']['race']['nom'][$inc]  = nom_race($_SESSION['persos']['race'][$inc], $_SESSION['persos']['nom_race'][$inc]);
			//$_SESSION['persos']['race']['nom'][$inc] 			= nom_race($_SESSION['persos']['race'][$inc]);
			
			if ($_SESSION['persos']['race'][$inc]==3 || $_SESSION['persos']['race'][$inc]==4) {
				if($_SESSION['persos']['grade'][$inc]==5){
					$_SESSION['persos']['anim']['id']		= $_SESSION['persos']['id'][$inc] ;
					$_SESSION['persos']['anim']['race']		= $_SESSION['persos']['race'][$inc];
					$_SESSION['persos']['anim']['grade']	= $_SESSION['persos']['grade'][$inc];
					}
				}elseif($_SESSION['persos']['race'][$inc]==1) {
					if($_SESSION['persos']['grade'][$inc]==4 || $_SESSION['persos']['grade'][$inc]==5){
						$_SESSION['persos']['anim']['id']		= $_SESSION['persos']['id'][$inc] ;
						$_SESSION['persos']['anim']['race']		= $_SESSION['persos']['race'][$inc];
						$_SESSION['persos']['anim']['grade']	= $_SESSION['persos']['grade'][$inc];
						}
					}
			
			
			if ($_SESSION['persos']['faction']['id'][$inc]){
				$sql="SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits
					FROM faction_membres
						INNER JOIN faction_grades ON faction_grades.faction_id = ".$persos['faction_id']." AND faction_grades.grade_id = faction_membres.faction_grade_id
						WHERE faction_membres.perso_id = ".$persos['id'];
				$res_fac = mysql_query ($sql) or die (mysql_error());
				$fac_droits = mysql_fetch_array ($res_fac);
				$_SESSION['persos']['faction']['grade'][$inc] 	= $fac_droits['faction_grade'];
				$_SESSION['persos']['faction']['droits'][$inc] 	= $fac_droits['droits'];
				}
			$_SESSION['persos']['superieur'][$inc++] = $persos['superieur_id'];
			}
	
$grille = grille_damier($id_utilisateur);

if($grille == true){
	$damier_grille = "damier_grille";
}else{
	$damier_grille = "";
}
		
//Remise à zero des valeurs de session.
$_SESSION['damier_persos']=NULL;
$_SESSION['damier_artefact']=NULL;
$_SESSION['damier_objet_complexe']=NULL;
$_SESSION['damier_objet_simple']=NULL;
$_SESSION['damier_porte']= NULL;

//------------------------------------------------------------------
if($is_spawn){
//Selection de tous les éléments présents dans le champ de vision
//Initialisation du nombre d'element par liste
$inc=0;

$liste_perso['case']					= NULL;
$liste_terrain['case']					= NULL;

$liste_artefact['case']					= NULL;
$liste_objet_simple['case']				= NULL;
$liste_objet_complexe['case']			= NULL;
$liste_porte['case']					= NULL;

$liste_perso['case']['inc']				= $inc;
$liste_terrain['case']['inc']			= $inc;

$liste_artefact['case']['inc']			= $inc;
$liste_objet_simple['case']['inc']		= $inc;
$liste_objet_complexe['case']['inc']	= $inc;
$liste_porte['case']['inc']				= $inc;


// Selection des elements de type perso
if($x_min>$x_max){
		$rchch_x ="(pos_x>='$x_min' OR pos_x<='$x_max')";
	}else{
		$rchch_x ="(pos_x>='$x_min' AND pos_x<='$x_max')";
		}
if($y_min>$y_max){
		$rchch_y ="(pos_y>='$y_min' OR pos_y<='$y_max')";
	}else{
		$rchch_y ="(pos_y>='$y_min' AND pos_y<='$y_max')";
		}
$sql="SELECT damier_persos.*, persos.*, 
		races.nom AS nom_grade, races.color AS color, races.camp_id AS camp, 
		factions.nom AS nom_faction, factions.logo_url AS logo, factions.type_nom AS type_faction, faction_grades.nom AS grade_faction
		FROM damier_persos 
			INNER JOIN persos ON persos.id=damier_persos.perso_id
			INNER JOIN races ON races.race_id=persos.race_id AND races.grade_id=persos.grade_id
			LEFT JOIN factions ON factions.id=persos.faction_id
			LEFT JOIN faction_membres ON faction_membres.faction_id=persos.faction_id AND faction_membres.perso_id=persos.id
			LEFT JOIN faction_grades ON faction_grades.faction_id=persos.faction_id AND faction_grades.grade_id = faction_membres.faction_grade_id				
				WHERE (carte_id='$carte_pos' AND $rchch_x AND $rchch_y)
				
				ORDER BY persos.nom ASC";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_perso['case']['inc']                	= $inc;

//Recupération du numero de perso courant
$inc_race = $_SESSION['persos']['id'][0];
//Recuperation de la race du perso courant
$cur_race = $_SESSION['persos']['race'][$inc_race];
$camp 	  = $_SESSION['persos']['camp'][$inc_race];

while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_perso['case']['inc']                	= $inc;
	$liste_perso['case']['pos_x'][$inc]        	= $info["pos_x"];
	$liste_perso['case']['pos_y'][$inc]        	= $info["pos_y"];
	$liste_perso['case']['carte_pos'][$inc]    	= $info["carte_id"];
	$liste_perso['case']['id'][$inc]     	   	= $info['perso_id'];

	$liste_perso['case']['icone'][$inc] 	   	= icone_persos($info['perso_id']);
	//$liste_perso['case']['galon'][$inc] 	   	= galon_persos($info['perso_id']);
	//$liste_perso['case']['grade'][$inc] 	   	= $info['grade_id'];
	$liste_perso['case']['galon'][$inc] 	   	= $info['galon_id'];

	//Selection des informations du personnage
	
	/*$sql="SELECT persos.nom, persos.faction_id AS faction_id, persos.mdj AS mdj, persos.race_id AS race_id, persos.grade_id AS grade_id,
				races.nom AS nom_grade, races.color AS color, races.camp_id AS camp
				FROM persos 
					INNER JOIN races ON races.race_id=persos.race_id AND races.grade_id=persos.grade_id
						WHERE persos.id=".$info['perso_id'];
	$res_perso = mysql_query ($sql) or die (mysql_error());*/
	//$perso = mysql_fetch_array ($res_perso);
	$liste_perso['case']['nom'][$inc]			=	$info['nom'];
	$liste_perso['case']['titre'][$inc]			=	$info['titre'];
	$liste_perso['case']['faction']['id'][$inc]	=	$info['faction_id'];
	$liste_perso['case']['race']['id'][$inc]	=	$info['race_id'];
	$liste_perso['case']['camp']['id'][$inc]	=	$info['camp'];
	$liste_perso['case']['grade']['id'][$inc]	=	$info['grade_id'];
	$liste_perso['case']['grade']['nom'][$inc]	=	$info['nom_grade'];
	$liste_perso['case']['color'][$inc]			=	$info['color'];
	$liste_perso['case']['mdj'][$inc]			=	$info['mdj'];
	
        $liste_perso['case']['race']['perso'][$inc]     =       $info['nom_race'];
        
	$liste_perso['case']['race']['nom'][$inc]	=	 nom_race($liste_perso['case']['race']['id'][$inc], $info['nom_race']);
	
	if($liste_perso['case']['faction']['id'][$inc]){
		/*$sql="SELECT persos.faction_id AS faction_id, factions.nom AS nom_faction, factions.logo_url AS logo, factions.type_nom AS type_faction,
					 faction_grades.nom AS grade_faction
					FROM persos 
						INNER JOIN factions ON factions.id=persos.faction_id
						INNER JOIN faction_membres ON faction_membres.faction_id=persos.faction_id AND faction_membres.perso_id=persos.id
						INNER JOIN faction_grades ON faction_grades.faction_id=persos.faction_id AND faction_grades.grade_id = faction_membres.faction_grade_id
							WHERE persos.id=".$info['perso_id'];
		$res_perso = mysql_query ($sql) or die (mysql_error());
		$perso = mysql_fetch_array ($res_perso);*/
		$liste_perso['case']['faction']['nom'][$inc]	=	$info['nom_faction'];
		$liste_perso['case']['faction']['logo'][$inc]	=	$info['logo'];
		$liste_perso['case']['faction']['type'][$inc]	=	$info['type_faction'];	
		$liste_perso['case']['faction']['grade'][$inc]	=	$info['grade_faction'];
		}
		//--- Mise en catégorie des cibles potentielles pour les actions ---//
		$liste_perso['case']['cible']['cac'][$inc]="None";
		
		if ($liste_perso['case']['camp']['id'][$inc] == $camp)
			{
			$liste_perso['case']['cible']['map'][$inc]="Allier";
			}
			else $liste_perso['case']['cible']['map'][$inc]="Ennemi";
		if(distance($info, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
		$liste_perso['case']['cible']['cac'][$inc]=$liste_perso['case']['cible']['map'][$inc];
		}
		
}

$_SESSION['damier_persos']=$liste_perso;

// Selection des elements de type terrain
if($x_min>$x_max){
		$rchch_x ="(pos_x>='$x_min' OR pos_x<='$x_max')";
	}else{
		$rchch_x ="(pos_x>='$x_min' AND pos_x<='$x_max')";
		}
if($y_min>$y_max){
		$rchch_y ="(pos_y>='$y_min' OR pos_y<='$y_max')";
	}else{
		$rchch_y ="(pos_y>='$y_min' AND pos_y<='$y_max')";
		}
		
	
$sql="SELECT * FROM damier_terrain 
	INNER JOIN case_terrain ON (case_terrain.id=damier_terrain.terrain_id) 
	WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;

$liste_terrain['case']['carte_pos'][$inc] = $liste_perso['case']['carte_pos'][1];
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_terrain['case']['inc']                 = $inc;
	$liste_terrain['case']['pos_x'][$inc]         = $info["pos_x"];
	$liste_terrain['case']['pos_y'][$inc]         = $info["pos_y"];
	$liste_terrain['case']['carte_pos'][$inc]     = $info["carte_id"];

	//$id = $info["terrain_id"];

	//$sql="SELECT * FROM case_terrain WHERE id=$id";
	//$res_icone = mysql_query ($sql) or die (mysql_error());
	//$icone = mysql_fetch_array ($res_icone);

	$liste_terrain['case']['icone'][$inc]= $info['image'];
	$liste_terrain['case']['nom'][$inc]= $info['nom'];
	$liste_terrain['case']['mouv'][$inc]= $info['mouv'];
}


// Selection des elements de type artefact
if($x_min>$x_max){
		$rchch_x ="(pos_x>='$x_min' OR pos_x<='$x_max')";
	}else{
		$rchch_x ="(pos_x>='$x_min' AND pos_x<='$x_max')";
		}
if($y_min>$y_max){
		$rchch_y ="(pos_y>='$y_min' OR pos_y<='$y_max')";
	}else{
		$rchch_y ="(pos_y>='$y_min' AND pos_y<='$y_max')";
		}
		
$sql="SELECT * FROM damier_artefact 
INNER JOIN case_artefact ON (case_artefact.id=damier_artefact.icone_artefact_id)
WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_artefact['case']['inc']                 	= $inc;
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_artefact['case']['inc']                 	= $inc;
	$liste_artefact['case']['object_id'][$inc]      = $info["icone_artefact_id"];
	$liste_artefact['case']['id'][$inc]            	= $info["id"];
	$liste_artefact['case']['pos_x'][$inc]         	= $info["pos_x"];
	$liste_artefact['case']['pos_y'][$inc]         	= $info["pos_y"];
	$liste_artefact['case']['carte_pos'][$inc]     	= $info["carte_id"];
	$liste_artefact['case']['pv'][$inc]     		= $info["pv"];
	if($liste_artefact['case']['pv'][$inc]==-1){
		$liste_artefact['case']['destructible'][$inc]="no";
	} else { 
		$liste_artefact['case']['destructible'][$inc]="yes";
	}

	//$id = $info["icone_artefact_id"];

	//$sql="SELECT * FROM case_artefact WHERE id=$id";
	//$res_icone = mysql_query ($sql) or die (mysql_error());
	//$icone = mysql_fetch_array ($res_icone);

	$liste_artefact['case']['nom'][$inc]= $info['nom'];
	$liste_artefact['case']['description'][$inc]= $info['description'];
	$liste_artefact['case']['pv_max'][$inc]= $info['pv_max'];
	$liste_artefact['case']['icone'][$inc]= $info['image'];
	$liste_artefact['rarete'][$inc]= $info['rarete'];	
	
	$liste_artefact['case']['cible']['cac'][$inc]="None";
			
	if(distance($info, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
		$liste_artefact['case']['cible']['cac'][$inc]="rammassable";
		}

}

$_SESSION['damier_artefact']=$liste_artefact;
	
// Selection des elements de type objet_complexe
if($x_min>$x_max){
		$rchch_x ="((pos_x>='$x_min' OR pos_x<='$x_max') OR (pos_x_max>='$x_min' OR pos_x_max<='$x_max') OR (pos_x_max>='$x_min' AND pos_x<='$x_max'))";
	}else{
		$rchch_x ="((pos_x>='$x_min' AND pos_x<='$x_max') OR (pos_x_max>='$x_min' AND pos_x_max<='$x_max') OR (pos_x_max>='$x_max' AND pos_x<='$x_min'))";
		}
if($y_min>$y_max){
		$rchch_y ="((pos_y>='$y_min' OR pos_y<='$y_max') OR (pos_y_max>='$y_min' OR pos_y_max<='$y_max') OR (pos_y_max>='$y_min' AND pos_y<='$y_max'))";
	}else{
		$rchch_y ="((pos_y>='$y_min' AND pos_y<='$y_max') OR (pos_y_max>='$y_min' AND pos_y_max<='$y_max') OR (pos_y_max>='$y_max' AND pos_y<='$y_min'))";
		}
		
$sql="SELECT * FROM damier_objet_complexe WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$ok_y=false;
$ok_x=false;
$liste_objet_complexe['case']['inc']                = $inc;
while($info = mysql_fetch_array ($resultat)){

$no=0;
for($incy=$info['pos_y_max'];$incy>=$info['pos_y'];$incy--)
	{
	if($y_min>$y_max){
		if(($incy<=$y_max_carte && $incy>=$y_min)||($incy<=$y_max && $incy>=$y_min_carte)){
			$ok_y=true;
			}
		} else {
			if($incy<=$y_max && $incy>=$y_min){
				$ok_y=true;
				}
			}
	if($ok_y){
		$ok_y=false;
		for($incx=$info['pos_x'];$incx<=$info['pos_x_max'];$incx++)
			{
			if($x_min>$x_max){
				if(($incx<=$x_max_carte && $incx>=$x_min)||($incx<=$x_max && $incx>=$x_min_carte)){
					$ok_x=true;
					}
				} else {
					if($incx<=$x_max && $incx>=$x_min){
						$ok_x=true;
						}
					}
			if($ok_x){
				$ok_x=false;
				$inc++;
				//Determination du No de l'image à afficher 
				// Pour une matrice n*m
				// No = m*K + L (K=0...n-1 ; L = 1...m)
				$no=($info['pos_x_max']-$info['pos_x']+1)*($info['pos_y_max']-$incy)+($incx-$info['pos_x']+1);
				
				$liste_objet_complexe['case']['inc']                = $inc;
				$liste_objet_complexe['case']['object_id'][$inc]    = $info["case_objet_complexe_id"];
				$liste_objet_complexe['case']['id'][$inc]          	= $info["id"];
				$liste_objet_complexe['case']['pos_x'][$inc]        = $incx;
				$liste_objet_complexe['case']['pos_y'][$inc]        = $incy;
				$liste_objet_complexe['case']['carte_pos'][$inc]    = $info["carte_id"];
				$liste_objet_complexe['case']['pv'][$inc]     		= $info["pv"];
				if($liste_objet_complexe['case']['pv'][$inc]==-1){
					$liste_objet_complexe['case']['destructible'][$inc]="no";
					} else $liste_objet_complexe['case']['destructible'][$inc]="yes";
				
				$id = $info["case_objet_complexe_id"];

				$sql="SELECT * FROM case_objet_complexe WHERE id=$id";
				$res_icone = mysql_query ($sql) or die (mysql_error());
				$icone = mysql_fetch_array ($res_icone);

				$liste_objet_complexe['case']['nom'][$inc]= $icone['nom'];
				$liste_objet_complexe['case']['description'][$inc]= $icone['description'];
				$liste_objet_complexe['case']['pv_max'][$inc]= $icone['pv_max'];
				$liste_objet_complexe['case']['bloquant'][$inc]= $icone['bloquant'];
				$liste_objet_complexe['case']['reparable'][$inc]= $icone['reparable'];	
				if($icone['nom']!="Abysses"){
					$liste_objet_complexe['case']['icone'][$inc]= $icone['images'].'_'.$no.'.png';
					} else {						
						$liste_objet_complexe['case']['bloquant'][$inc]= 2;
						$liste_objet_complexe['case']['icone'][$inc]= $icone['images'].'.jpg';
						}
				
				$liste_objet_complexe['case']['cible']['cac'][$inc]="None";
					
				if(distance( array("pos_x"=>$liste_objet_complexe['case']['pos_x'][$inc],"pos_y"=>$liste_objet_complexe['case']['pos_y'][$inc]), array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
					$liste_objet_complexe['case']['cible']['cac'][$inc]="cible";
					}
					
				}
			}
		}
	}
}
$_SESSION['damier_objet_complexe']=$liste_objet_complexe;
	
// Selection des elements de type objet_simple
if($x_min>$x_max){
		$rchch_x ="(pos_x>='$x_min' OR pos_x<='$x_max')";
	}else{
		$rchch_x ="(pos_x>='$x_min' AND pos_x<='$x_max')";
		}
if($y_min>$y_max){
		$rchch_y ="(pos_y>='$y_min' OR pos_y<='$y_max')";
	}else{
		$rchch_y ="(pos_y>='$y_min' AND pos_y<='$y_max')";
		}
		
$sql="SELECT * FROM damier_objet_simple 
INNER JOIN case_objet_simple ON (case_objet_simple.id=damier_objet_simple.case_objet_simple_id)
WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_objet_simple['case']['inc']                 = $inc;
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_objet_simple['case']['inc']                	= $inc;
	$liste_objet_simple['case']['object_id'][$inc]      = $info["case_objet_simple_id"];
	$liste_objet_simple['case']['id'][$inc]           	= $info["id"];
	$liste_objet_simple['case']['pos_x'][$inc]         	= $info["pos_x"];
	$liste_objet_simple['case']['pos_y'][$inc]         	= $info["pos_y"];
	$liste_objet_simple['case']['carte_pos'][$inc]     	= $info["carte_id"];
	$liste_objet_simple['case']['pv'][$inc]     		= $info["pv"];
	if($liste_objet_simple['case']['pv'][$inc]==-1){
		$liste_objet_simple['case']['destructible'][$inc]="no";
	} else { 
		$liste_objet_simple['case']['destructible'][$inc]="yes"; 
	}
	//$id = $info["case_objet_simple_id"];

	//$sql="SELECT * FROM case_objet_simple WHERE id=$id";
	//$res_icone = mysql_query ($sql) or die (mysql_error());
	//$icone = mysql_fetch_array ($res_icone);

	$liste_objet_simple['case']['icone'][$inc]= $info['image'];
	$liste_objet_simple['case']['nom'][$inc]= $info['nom'];
	$liste_objet_simple['case']['description'][$inc]= $info['description'];
	$liste_objet_simple['case']['pv_max'][$inc]= $info['pv_max'];
	$liste_objet_simple['case']['bloquant'][$inc]= $info['bloquant'];	
	
	$liste_objet_simple['case']['cible']['cac'][$inc]="None";
	
	if(distance($info, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
		$liste_objet_simple['case']['cible']['cac'][$inc]="cible";
		}
}
$_SESSION['damier_objet_simple']=$liste_objet_simple;


// Selection des elements de type porte
if($x_min>$x_max){
		$rchch_x ="((pos_x>='$x_min' OR pos_x<='$x_max') OR (pos_x>($x_min-4) OR pos_x<($x_max-4)))";
	}else{
		$rchch_x ="((pos_x>='$x_min' AND pos_x<='$x_max') OR (pos_x>($x_min-4) AND pos_x<($x_max-4)))";
		}
if($y_min>$y_max){
		$rchch_y ="((pos_y>='$y_min' OR pos_y<='$y_max') OR (pos_y>($y_min+4) OR pos_y<($y_max+4)))";
	}else{
		$rchch_y ="((pos_y>='$y_min' AND pos_y<='$y_max') OR (pos_y>($y_min+4) AND pos_y<($y_max+4)))";
		}
		
$sql="SELECT * FROM damier_porte WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_porte['case']['inc']                 = $inc;
while($info = mysql_fetch_array ($resultat)){

$no=0;
for($incy=0;$incy<=3;$incy++)
	{
	for($incx=0;$incx<=3;$incx++)
		{
		$inc++;
		$no++;
		$liste_porte['case']['inc']                 = $inc;
		$liste_porte['case']['id'][$inc]           	= $info['id'];
		$liste_porte['case']['nom'][$inc]           = $info['nom'];
		$liste_porte['case']['pos_x'][$inc]         = $info["pos_x"]+$incx;
		$liste_porte['case']['pos_y'][$inc]         = $info["pos_y"]-$incy;
		$liste_porte['case']['carte_pos'][$inc]     = $info["carte_id"];
		$liste_porte['case']['type'][$inc]     		= $info['nom_image'];
		$liste_porte['case']['description'][$inc]	= $info['description'];
		$liste_porte['case']['pv_max'][$inc]		= $info['pv_max'];
		$liste_porte['case']['pv'][$inc]     		= $info["pv"];
		if($liste_porte['case']['pv'][$inc]==-1){
			$liste_porte['case']['destructible'][$inc]="no";
			} else $liste_porte['case']['destructible'][$inc]="yes";
		
		$liste_porte['case']['statut'][$inc]     	= $info["statut"];
		$liste_porte['case']['spawn'][$inc]     	= $info["spawn_id"];
		$liste_porte['case']['icone'][$inc]			= 'decors/portes/'.$info['nom_image'].'_'.$no.'.png';
		
		$liste_porte['case']['cible']['cac'][$inc]="None";
			
		if(distance(array("pos_x"=>$liste_porte['case']['pos_x'][$inc],"pos_y"=>$liste_porte['case']['pos_y'][$inc]), array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
			$liste_porte['case']['cible']['cac'][$inc]="cac";
			}
		}
	}

}
$_SESSION['damier_porte']= $liste_porte;


// Selection des elements de type bouclier
	// Type 2
	if($x_min>$x_max){
			$rchch_x ="((pos_x>='$x_min' OR pos_x<='$x_max') OR (pos_x>($x_min-4) OR pos_x<($x_max -4)))";
		}else{
			$rchch_x ="((pos_x>='$x_min' AND pos_x<='$x_max') OR (pos_x>($x_min-4) AND pos_x<($x_max-4)))";
			}
	if($y_min>$y_max){
			$rchch_y ="((pos_y>='$y_min' OR pos_y<='$y_max') OR (pos_y>($y_min+4) OR pos_y<($y_max+4)))";
		}else{
			$rchch_y ="((pos_y>='$y_min' AND pos_y<='$y_max') OR (pos_y>($y_min+4) AND pos_y<($y_max+4)))";
			}
			
	$sql="SELECT * FROM damier_bouclier WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	
$inc=0;
$liste_bouclier['case']['inc']                 = $inc;

	while($info = mysql_fetch_array ($resultat)){

	$no=0;
	for($incy=0;$incy<=($info['type_id']-1);$incy++)
		{
		for($incx=0;$incx<=($info['type_id']-1);$incx++)
			{
			$inc++;
			$no++;
			$liste_bouclier['case']['inc']                 	= $inc;
			$liste_bouclier['case']['id'][$inc]           	= $info['id'];
			$liste_bouclier['case']['nom'][$inc]           	= $info['nom'];
			$liste_bouclier['case']['nom_image'][$inc]     	= $info['nom_image'];
			$liste_bouclier['case']['pos_x'][$inc]         	= $info["pos_x"]+$incx;
			$liste_bouclier['case']['pos_y'][$inc]         	= $info["pos_y"]-$incy;
			$liste_bouclier['case']['carte_pos'][$inc]     	= $info["carte_id"];

			$liste_bouclier['case']['description'][$inc]	= $info['description'];
			$liste_bouclier['case']['pv_max'][$inc]			= $info['pv_max'];
			$liste_bouclier['case']['pv'][$inc]     		= $info["pv"];
			
			if($liste_bouclier['case']['pv'][$inc]==-1){
				$liste_bouclier['case']['destructible'][$inc]="no";
				} else $liste_bouclier['case']['destructible'][$inc]="yes";
			
			$liste_bouclier['case']['statut'][$inc]     	= $info["statut"];
			$liste_bouclier['case']['type_id'][$inc]     	= $info["type_id"];
			$liste_bouclier['case']['icone'][$inc]			= 'decors/boucliers/'.$info['nom_image'].'_'.$no.'.png';
			
			$liste_bouclier['case']['cible']['cac'][$inc]="None";
				
			if(distance( array("pos_x"=>$liste_bouclier['case']['pos_x'][$inc],"pos_y"=>$liste_bouclier['case']['pos_y'][$inc]), array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1){
				$liste_bouclier['case']['cible']['cac'][$inc]="cac";
				}
			}
		}

	}
$_SESSION['damier_bouclier']= $liste_bouclier;

//------------------------------------------------------------------

//Fonction de recherche de l'image dans une liste
function rchch_case($pos_x_case, $pos_y_case, $plan, $liste){
$val[0] = 0;
$compte = 0;

for($inc=1 ; $inc<=$liste['case']['inc'] ; $inc++){
    if($liste['case']['pos_x'][$inc]==$pos_x_case && $liste['case']['pos_y'][$inc]==$pos_y_case && $liste['case']['carte_pos'][$inc]==$plan){
        $val[$compte++]=$inc;
        }
    }
if ($val[0] == 0){
	return NULL;
	}
    else {
	$inc=0;
		while($compte-$inc){
			$retour['img'][$inc] 	= $liste['case']['icone'][$val[$inc]];
			if(isset($liste['case']['galon'][$val[$inc]])){
				$retour['galon'][$inc] = $liste['case']['galon'][$val[$inc]];
			}
			if(isset($liste['case']['grade']['id'][$val[$inc]])){
				$retour['grade'][$inc] = $liste['case']['grade']['id'][$val[$inc]];
			}			
			
							
			$retour['id'][$inc]	= $val[$inc];
			$inc++;
			}
			return $retour;
		}
}

//Fonction de determination du cout de déplacement sur une case
// La valeur est mise dans une variable de session pour être réutilisée plus tard
function rchch_cout($pos_x_case, $pos_y_case, $plan, $liste_perso, $liste_terrain, $liste_objet_simple, $liste_objet_complexe, $liste_bouclier, $decors = null){
$val = 1;
for($inc=1 ; $inc<=$liste_terrain['case']['inc'] ; $inc++){
    if($liste_terrain['case']['pos_x'][$inc]==$pos_x_case && $liste_terrain['case']['pos_y'][$inc]==$pos_y_case){
        $val=$liste_terrain['case']['mouv'][$inc];
        break;
        }
   }
for($inc=1 ; $inc<=$liste_objet_simple['case']['inc'] ; $inc++){
    if($liste_objet_simple['case']['pos_x'][$inc]==$pos_x_case && $liste_objet_simple['case']['pos_y'][$inc]==$pos_y_case && $liste_objet_simple['case']['bloquant'][$inc]==1){
        $val=-1;
        break;
        }
   }
   
for($inc=1 ; $inc<=$liste_objet_complexe['case']['inc'] ; $inc++){
    if($liste_objet_complexe['case']['pos_x'][$inc]==$pos_x_case && $liste_objet_complexe['case']['pos_y'][$inc]==$pos_y_case && $liste_objet_complexe['case']['bloquant'][$inc]==1){
        $val=-1;	
        break;
        }
		elseif($liste_objet_complexe['case']['pos_x'][$inc]==$pos_x_case && $liste_objet_complexe['case']['pos_y'][$inc]==$pos_y_case && $liste_objet_complexe['case']['bloquant'][$inc]==2){
			$val=-2;
		break;
		}
   }
   
for($inc=1 ; $inc<=$liste_bouclier['case']['inc'] ; $inc++){
    if($liste_bouclier['case']['pos_x'][$inc]==$pos_x_case && $liste_bouclier['case']['pos_y'][$inc]==$pos_y_case){
        $val=-1;
        break;
        }
   }

for($inc=1 ; $inc<=$liste_perso['case']['inc'] ; $inc++){
    if($liste_perso['case']['pos_x'][$inc]==$pos_x_case && $liste_perso['case']['pos_y'][$inc]==$pos_y_case){
        $val=-1;
        break;
        }
   }
   
if($val > 0 && $decors != null) {   

    $case = $decors->getCase($pos_x_case, $pos_y_case);

    if($case) {
        // fix, en attendant les décors VF
        if($case == 'eau') {
            $val *= 2;
        }
    }
}
   
$_SESSION['cout'][$pos_x_case][$pos_y_case]=$val;

return $val;
}

// Fonction déterminant les informations à placer dans l'infobulle

function afficher_bulle($infos0, $infos1, $infos2, $mdj, $zindex){
	if(!empty($mdj)){
		$mdjs = $mdj['mdj'];
		$p_id = $mdj['id'];
	}else{
		$mdjs = '';
		$p_id = '';
	}
	global $perso_id;
	echo "<div class='damier_bulle formulaire'  id='".$p_id."_damierbulle' style='z-index:9999;'>
					<div class='bubulle'>
					<img src='../images/damier_vide.png' alt='conteneur' />
						<div class='infobulle' id='".$p_id."_bulle'>
							<table border='0px' CELLPADDING='0' CELLSPACING='0'>
								<tr>
									<td colspan='3' class='haut_bulle'></td>
								</tr>
								<tr>
									<td class='gauche_bulle'>".$infos0."</td>
									<td class='middle_bulle'>
										<img class='curspointer' src='../images/damier_vide.png' alt='case' onclick=\"infobulle_verouille(".$p_id.", ".$zindex.");\" />
									</td>
									<td class='droit_bulle'></td>	
								</tr>
								<tr>
									<td colspan='3' class='centre_bulle'>
										".$infos1.$infos2;
						if ($perso_id == $p_id){
							echo "<span id='".$p_id."_mdj_p' class='curspointer'><span id='".$p_id."_mdj' ondblclick=\"edition_click_mdj(this.id);\">".$mdjs."</span></span>";
						}else{
							echo $mdjs;
						}
				   	echo "</td>
								</tr>	
								<tr>
									<td colspan='3' class='bas_bulle'></td>	
								</tr>
							</table>
						</div>
					</div>
				</div>";
}

function infobulle($type, $id, $cout, $liste_perso, $liste_terrain, $liste_artefact, $liste_objet_simple, $liste_objet_complexe, $liste_porte, $liste_bouclier,$zindex){

// Infos pour les personnages

if ($type=='perso'){
	$infos='<b>Nom : </b><a href="'.SERVER_URL.'/persos/event/?id='.$liste_perso['case']['id'][$id].'">'.$liste_perso['case']['nom'][$id].'</a><br/>';
			
	if(isset($liste_perso['case']['titre'][$id])) {
		$infos .= '<i>'.$liste_perso['case']['titre'][$id].'</i><br/>';
	}
	$infos .= '<b>Mat. : </b><a href="'.SERVER_URL.'/messagerie/?id='.$_SESSION['persos']['current_id'].'&dest='.$liste_perso['case']['id'][$id].'">'.$liste_perso['case']['id'][$id].'</a><br/>
	<b>Race : </b>'.$liste_perso['case']['race']['nom'][$id].'<br/>';
        
        $grade_affichage = ($liste_perso['case']['race']['perso'][$id]) ? "Grade " . $liste_perso['case']['grade']['id'][$id] : $liste_perso['case']['grade']['nom'][$id].' ('.$liste_perso['case']['grade']['id'][$id].')' ;
        
	$infos .= '<b>Grade : </b>'.$grade_affichage.'<br/>
			';
	if($liste_perso['case']['faction']['id'][$id]>0 && $liste_perso['case']['grade']['id'][$id]>=0){
		if($liste_perso['case']['faction']['logo'][$id]!=''){
			  $img='<img src="'.SERVER_URL.'/images/'.$liste_perso['case']['faction']['logo'][$id].'">';
				}
				else $img='<img src="'.SERVER_URL.'/images/'.$liste_perso['case']['icone'][$id].'">';
		$info_faction='<hr><b>Faction : </b>'.$liste_perso['case']['faction']['nom'][$id].'<br/>
						<b>Type : </b>'.$liste_perso['case']['faction']['type'][$id].'<br/>
						<b>Grade : </b>'.$liste_perso['case']['faction']['grade'][$id].'<br/>';
		}
		else {
			$img='<img src="'.SERVER_URL.'/images/'.$liste_perso['case']['icone'][$id].'" alt="icone personnage">';
			$info_faction='<br/><b>N\'appartient &agrave; aucune faction</b><br/>';
			}
	if($liste_perso['case']['mdj'][$id]!=''){
			$mdj['mdj']= $liste_perso['case']['mdj'][$id];
			$mdj['id'] = $liste_perso['case']['id'][$id];
		}else{
			$mdj['mdj'] = '-Mdj-';
			$mdj['id'] = $liste_perso['case']['id'][$id];
		}
		
		afficher_bulle($img,$infos,$info_faction,$mdj,$zindex);
		//echo $head.$img.$infos.$info_faction.$mdj.$foot;
	}
	
	// Infos pour les objets simples
	
if ($type=='objet_simple'){
	//$img='<img src="./../images/'.$liste_objet_simple['case']['icone'][$id].'">';
	$nom='<b>Nom : </b>'.$liste_objet_simple['case']['nom'][$id]." (Mat.".$liste_objet_simple['case']['id'][$id].")".'<br/>';
	$infos='<b>Description : </b>'.$liste_objet_simple['case']['description'][$id].'<br/><br/>';
	if($liste_objet_simple['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.($liste_objet_simple['case']['pv'][$id] /
		$liste_objet_simple['case']['pv_max'][$id])*100 .' %<br/><br/>';
		
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
			
	if($cout<0){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
		afficher_bulle($nom,$infos,$mouv,'',$zindex);
	}
	
	// Infos pour les objets complexes
	
if ($type=='objet_complexe'){
	//$img='<img src="./../images/'.$liste_objet_complexe['case']['icone'][$id].'">';
	$nom='<b>Nom : </b>'.$liste_objet_complexe['case']['nom'][$id]." (Mat.".$liste_objet_complexe['case']['id'][$id].")".'<br/>';
	$infos='<b>Description : </b>'.$liste_objet_complexe['case']['description'][$id].'<br/><br/>';
	if($liste_objet_complexe['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.($liste_objet_complexe['case']['pv'][$id] /$liste_objet_complexe['case']['pv_max'][$id])*100 .' %<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	if($cout<0){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	if(!($cout>=0 && $liste_objet_complexe['case']['destructible'][$id]!="yes")){
		afficher_bulle($nom,$infos,$mouv,'',$zindex);
		}
	}
	
	// Infos pour les boucliers
	
if ($type=='bouclier'){
	//$img='<img src="./../images/'.$liste_bouclier['case']['icone'][$id].'">';
	$nom='<b>Nom : </b>'.$liste_bouclier['case']['nom'][$id].'<br/>';
	$infos='<b>Description : </b>'.$liste_bouclier['case']['description'][$id].'<br/><br/>';
	if($liste_bouclier['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.($liste_bouclier['case']['pv'][$id] /  $liste_bouclier['case']['pv_max'][$id])*100 .' %<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	if($cout<0){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	afficher_bulle($nom,$infos,$mouv,'',$zindex,$zindex);
	}
	
	// Infos pour les artefacts
	
if ($type=='artefact'){
	//$img='<img src="./../images/'.$liste_artefact['case']['icone'][$id].'">';
	$nom='<b>Nom : </b>'.$liste_artefact['case']['nom'][$id].'<br/>';
	$infos='<b>Description : </b>'.$liste_artefact['case']['description'][$id].'<br/><br/>
			<b>Raret&eacute; : </b>'.$liste_artefact['rarete'][$id].'%<br/><br/>';
	if($liste_artefact['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.($liste_artefact['case']['pv'][$id] /  $liste_artefact['case']['pv_max'][$id])*100 .' %<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	if($cout<0){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	afficher_bulle($nom,$infos,$mouv,'',$zindex);
	}
	
	// Infos pour les portes
	
if ($type=='porte'){
	//$img='<img src="./../images/'.$liste_porte['case']['icone'][$id].'">';
	$nom ='<b>Nom : </b>'.$liste_porte['case']['nom'][$id].'<br/><br/>';
	$infos = '<b>Description : </b>'.$liste_porte['case']['description'][$id].'<br/><br/>';
	if($liste_porte['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.($liste_porte['case']['pv'][$id] / $liste_porte['case']['pv_max'][$id])*100 .' %<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';	
			
			
	if($liste_porte['case']['statut'][$id]){
		$mouv='<b>La porte est ouverte.</b>';
		}
		else $mouv='<b>Porte ferm&eacute;e, vous ne pouvez aller vers un autre plan</b>';
	afficher_bulle($nom,$infos,$mouv,'',$zindex);
	}
	
	// Infos pour les terrains
/*	
if ($type=='terrain'){
	if($id>0){
		$img='<img src="./../images/'.$liste_terrain['case']['icone'][$id].'">';
		$infos='<b>Type de terrain : </b>'.$liste_terrain['case']['nom'][$id].'<br/>';
		$mouv='<br/><b>Cout de d&eacute;placement : </b>'.$liste_terrain['case']['mouv'][$id].'<br/>';
		}
		else {
				if($liste_terrain['case']['carte_pos'][$id]==1)
					{
						$img='<img src="./../images/decors/motifs/pattern_grass.jpg">';
					}
					elseif($liste_terrain['case']['carte_pos'][$id]==2)
					{
						$img='<img src="./../images/decors/motifs/pattern_grass.jpg">';
					}
					elseif($liste_terrain['case']['carte_pos'][$id]==3)
					{
						$img='<img src="./../images/decors/motifs/pattern_grass.jpg">';
					}
				$infos='<b>Type de terrain : </b>Terrain commun<br/>';
				$mouv='<br/><b>Cout de d&eacute;placement : </b>1<br/>';
				}
		echo $head.$img.$infos.$mouv.$foot;
	}*/
}
}
?>
