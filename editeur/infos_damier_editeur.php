<?php
$image_loc = "./../images/";

//Remise à zero des valeurs de session.
$_SESSION['damier_persos']					= NULL;
$_SESSION['damier_artefact']				= NULL;
$_SESSION['damier_objet_complexe']  = NULL;
$_SESSION['damier_objet_simple']		= NULL;
$_SESSION['damier_porte']						= NULL;

//------------------------------------------------------------------
if($is_spawn){
//Selection de tous les éléments présents dans le champ de vision
//Initialisation du nombre d'element par liste
$inc=0;

$liste_terrain['case']				= NULL;

$liste_artefact['case']				= NULL;
$liste_objet_simple['case']		= NULL;
$liste_objet_complexe['case']	= NULL;
$liste_porte['case']					= NULL;

$liste_terrain['case']['inc']	= $inc;

$liste_artefact['case']['inc']				= $inc;
$liste_objet_simple['case']['inc']		= $inc;
$liste_objet_complexe['case']['inc']	= $inc;
$liste_porte['case']['inc']						= $inc;

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
		
	
$sql="SELECT * FROM damier_terrain WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
//$liste_terrain['case']['carte_pos'][$inc] = $liste_perso['case']['carte_pos'][1];
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_terrain['case']['inc']                 = $inc;
	$liste_terrain['case']['pos_x'][$inc]         = $info["pos_x"];
	$liste_terrain['case']['pos_y'][$inc]         = $info["pos_y"];
	$liste_terrain['case']['carte_pos'][$inc]     = $info["carte_id"];

	$id = $info["terrain_id"];

	$sql="SELECT * FROM case_terrain WHERE id=$id";
	$res_icone = mysql_query ($sql) or die (mysql_error());
	$icone = mysql_fetch_array ($res_icone);

	$liste_terrain['case']['icone'][$inc]= $icone['image'];
	$liste_terrain['case']['nom'][$inc]= $icone['nom'];
	$liste_terrain['case']['mouv'][$inc]= $icone['mouv'];
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
		
$sql="SELECT * FROM damier_artefact WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_artefact['case']['inc']                 	= $inc;
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_artefact['case']['inc']                 	= $inc;
	$liste_artefact['case']['id'][$inc]            	= $info["icone_artefact_id"];
	$liste_artefact['case']['pos_x'][$inc]         	= $info["pos_x"];
	$liste_artefact['case']['pos_y'][$inc]         	= $info["pos_y"];
	$liste_artefact['case']['carte_pos'][$inc]     	= $info["carte_id"];
	$liste_artefact['case']['pv'][$inc]     		= $info["pv"];
	if($liste_artefact['case']['pv'][$inc]==-1){
		$liste_artefact['case']['destructible'][$inc]="no";
		} else $liste_artefact['case']['destructible'][$inc]="yes";

	$id = $info["icone_artefact_id"];

	$sql="SELECT * FROM case_artefact WHERE id=$id";
	$res_icone = mysql_query ($sql) or die (mysql_error());
	$icone = mysql_fetch_array ($res_icone);

	$liste_artefact['case']['nom'][$inc]= $icone['nom'];
	$liste_artefact['case']['description'][$inc]= $icone['description'];
	$liste_artefact['case']['pv_max'][$inc]= $icone['pv_max'];
	$liste_artefact['case']['icone'][$inc]= $icone['image'];
	$liste_artefact['rarete'][$inc]= $icone['rarete'];	
	
	$liste_artefact['case']['cible']['cac'][$inc]="None";
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
				$no++;
				$liste_objet_complexe['case']['inc']                = $inc;
				$liste_objet_complexe['case']['id'][$inc]          	= $info["case_objet_complexe_id"];
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
				if($icone['nom']!="Abysses"){
					$liste_objet_complexe['case']['icone'][$inc]= $icone['images'].'_'.$no.'.png';
					} else $liste_objet_complexe['case']['icone'][$inc]= $icone['images'].'.jpg';
				$liste_objet_complexe['case']['description'][$inc]= $icone['description'];
				$liste_objet_complexe['case']['pv_max'][$inc]= $icone['pv_max'];
				$liste_objet_complexe['case']['bloquant'][$inc]= $icone['bloquant'];
				$liste_objet_complexe['case']['reparable'][$inc]= $icone['reparable'];		
				
				$liste_objet_complexe['case']['cible']['cac'][$inc]="None";
					
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
		
$sql="SELECT * FROM damier_objet_simple WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());

$inc=0;
$liste_objet_simple['case']['inc']                 = $inc;
while($info = mysql_fetch_array ($resultat)){

	$inc++;
	$liste_objet_simple['case']['inc']                	= $inc;
	$liste_objet_simple['case']['id'][$inc]           	= $info["case_objet_simple_id"];
	$liste_objet_simple['case']['pos_x'][$inc]         	= $info["pos_x"];
	$liste_objet_simple['case']['pos_y'][$inc]         	= $info["pos_y"];
	$liste_objet_simple['case']['carte_pos'][$inc]     	= $info["carte_id"];
	$liste_objet_simple['case']['pv'][$inc]     		= $info["pv"];
	if($liste_objet_simple['case']['pv'][$inc]==-1){
		$liste_objet_simple['case']['destructible'][$inc]="no";
		} else $liste_objet_simple['case']['destructible'][$inc]="yes";
	$id = $info["case_objet_simple_id"];

	$sql="SELECT * FROM case_objet_simple WHERE id=$id";
	$res_icone = mysql_query ($sql) or die (mysql_error());
	$icone = mysql_fetch_array ($res_icone);

	$liste_objet_simple['case']['icone'][$inc]= $icone['image'];
	$liste_objet_simple['case']['nom'][$inc]= $icone['nom'];
	$liste_objet_simple['case']['description'][$inc]= $icone['description'];
	$liste_objet_simple['case']['pv_max'][$inc]= $icone['pv_max'];
	$liste_objet_simple['case']['bloquant'][$inc]= $icone['bloquant'];	
	
	$liste_objet_simple['case']['cible']['cac'][$inc]="None";
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
			
			}
		}

	}
$_SESSION['damier_bouclier']= $liste_bouclier;

//------------------------------------------------------------------

//Fonction de recherche de l'image dans une liste
function rchch_case($pos_x_case, $pos_y_case, $plan, $liste){
$val = 0;

for($inc=1 ; $inc<=$liste['case']['inc'] ; $inc++){
    if($liste['case']['pos_x'][$inc]==$pos_x_case && $liste['case']['pos_y'][$inc]==$pos_y_case && $liste['case']['carte_pos'][$inc]==$plan){
        $val=$inc;
        break;
        }
    }
if ($val == 0){
	return NULL;
	}
    else {
			$retour['img'] 	= $liste['case']['icone'][$val];
			if(isset($liste['case']['galon'][$val])){
				$retour['galon'] = $liste['case']['galon'][$val];
				}
			$retour['id']	= $val;
			return $retour;
		}
}

//Fonction de determination du cout de déplacement sur une case
// La valeur est mise dans une variable de session pour être réutilisée plus tard
function rchch_cout($pos_x_case, $pos_y_case, $plan, $liste_perso, $liste_terrain, $liste_objet_simple, $liste_objet_complexe, $liste_bouclier){
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
   
$_SESSION['cout'][$pos_x_case][$pos_y_case]=$val;

return $val;
}

// Fonction déterminant les informations à placer dans l'infobulle
function infobulle($type, $id, $cout, $liste_perso, $liste_terrain, $liste_artefact, $liste_objet_simple, $liste_objet_complexe, $liste_porte, $liste_bouclier){

	$head='<div class="damier_bulle"><span>';
	$foot='</span></div>';

	// Infos pour les personnages

if ($type=='perso'){
	$infos='<b>Nom : </b>'.$liste_perso['case']['nom'][$id].'<br/>
			<b>Mat. : </b>'.$liste_perso['case']['id'][$id].'<br/>
			<b>Race : </b>'.$liste_perso['case']['race']['nom'][$id].'<br/>
			<b>Grade : </b>'.$liste_perso['case']['grade']['nom'][$id].' ('.$liste_perso['case']['grade']['id'][$id].')<br/>
			';
	if($liste_perso['case']['faction']['id'][$id]>0 && $liste_perso['case']['grade']['id'][$id]>=0){
		if($liste_perso['case']['faction']['logo'][$id]!=''){
			  $img='<img src="./../images/'.$liste_perso['case']['faction']['logo'][$id].'">';
				}
				else $img='<img src="./../images/'.$liste_perso['case']['icone'][$id].'">';
		$info_faction='<hr><b>Faction : </b>'.$liste_perso['case']['faction']['nom'][$id].'<br/>
						<b>Type : </b>'.$liste_perso['case']['faction']['type'][$id].'<br/>
						<b>Grade : </b>'.$liste_perso['case']['faction']['grade'][$id].'<br/>';
		}
		else {
			$img='<img src="./../images/'.$liste_perso['case']['icone'][$id].'">';
			$info_faction='<br/><b>N\'appartient &agrave; aucune faction</b><br/>';
			}
	if($liste_perso['case']['mdj'][$id]!=''){
		$mdj='<hr><div align="center">Mdj :</div><div id="mdj_bulle_'.$liste_perso['case']['id'][$id].'">'.$liste_perso['case']['mdj'][$id].'</div>';
		}else $mdj='<div id="mdj_1bulle_'.$liste_perso['case']['id'][$id].'"></div>';
	
	echo $head.$img.$infos.$info_faction.$mdj.$foot;
	}
	
	// Infos pour les objets simples
	
if ($type=='objet_simple'){
	$img='<img src="./../images/'.$liste_objet_simple['case']['icone'][$id].'">';
	$infos='<b>Nom : </b>'.$liste_objet_simple['case']['nom'][$id].'<br/>
			<b>Decription : </b>'.$liste_objet_simple['case']['description'][$id].'<br/><br/>';
	if($liste_objet_simple['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.$liste_objet_simple['case']['pv'][$id].'/'.$liste_objet_simple['case']['pv_max'][$id].'<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
			
	if($liste_objet_simple['case']['bloquant'][$id]){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	echo $head.$img.$infos.$mouv.$foot;
	}
	
	// Infos pour les objets complexes
	
if ($type=='objet_complexe'){
	$img='<img src="./../images/'.$liste_objet_complexe['case']['icone'][$id].'">';
	$infos='<b>Nom : </b>'.$liste_objet_complexe['case']['nom'][$id].'<br/>
			<b>Decription : </b>'.$liste_objet_complexe['case']['description'][$id].'<br/><br/>';
	if($liste_objet_complexe['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.$liste_objet_complexe['case']['pv'][$id].'/'.$liste_objet_complexe['case']['pv_max'][$id].'<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	if($liste_objet_complexe['case']['bloquant'][$id]){
		$mouv='<b>Vous ne pouvez pas passer ici.</b>';
		}
		else $mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	echo $head.$img.$infos.$mouv.$foot;
	}
	
	// Infos pour les boucliers
	
if ($type=='bouclier'){
	$img='<img src="./../images/'.$liste_bouclier['case']['icone'][$id].'">';
	$infos='<b>Nom : </b>'.$liste_bouclier['case']['nom'][$id].'<br/>
			<b>Decription : </b>'.$liste_bouclier['case']['description'][$id].'<br/><br/>';
	if($liste_bouclier['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.$liste_bouclier['case']['pv'][$id].'/'.$liste_bouclier['case']['pv_max'][$id].'<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	$mouv='<b>Vous ne pouvez pas passer ici.</b>';
	echo $head.$img.$infos.$mouv.$foot;
	}
	
	// Infos pour les artefacts
	
if ($type=='artefact'){
	$img='<img src="./../images/'.$liste_artefact['case']['icone'][$id].'">';
	$infos='<b>Nom : </b>'.$liste_artefact['case']['nom'][$id].'<br/>
			<b>Decription : </b>'.$liste_artefact['case']['description'][$id].'<br/><br/>
			<b>Raret&eacute; : </b>'.$liste_artefact['rarete'][$id].'%<br/><br/>';
	if($liste_artefact['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>'.$liste_artefact['case']['pv'][$id].'/'.$liste_artefact['case']['pv_max'][$id].'<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	$mouv='<b>Cout de d&eacute;placement : </b>'.$cout;
	echo $head.$img.$infos.$mouv.$foot;
	}
	
	// Infos pour les portes
	
if ($type=='porte'){
	$img='<img src="./../images/'.$liste_porte['case']['icone'][$id].'">';
	$infos='<b>Nom : </b>'.$liste_porte['case']['nom'][$id].'<br/><br/>
			<b>Decription : </b>'.$liste_porte['case']['description'][$id].'<br/><br/>';
	if($liste_porte['case']['destructible'][$id]=="yes"){
		$infos = $infos.'<b>Solidit&eacute; : </b>?????/?????<br/><br/>';
			}
			else $infos = $infos.'Cet objet est indestructible.<br/><br/>';
	if($liste_porte['case']['statut'][$id]){
		$mouv='<b>La porte est ouverte.</b>';
		}
		else $mouv='<b>Porte ferm&eacute;e, vous ne pouvez aller vers un autre plan</b>';
	echo $head.$img.$infos.$mouv.$foot;
	}
	
	// Infos pour les terrains
	
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
	}
}
}
?>
