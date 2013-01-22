<?php
ControleAcces('utilisateur',1);

$perso_id 	= $_SESSION['persos']['current_id'];
$id 		= $_SESSION['persos']['id'][0];
$nom 		= $_SESSION['persos']['nom'][$id];
$race 		= $_SESSION['persos']['race'][$id];
$grade 		= $_SESSION['persos']['grade'][$id];
$galon 		= $_SESSION['persos']['galon'][$id];
$affil 		= $_SESSION['persos']['superieur'][$id];

$niv_mag = $caracs['niv'];
$cercle = $caracs['cercle'];

if($is_spawn){

$pos_x_perso    = $_SESSION['persos']['pos_x'][$id];
$pos_y_perso    = $_SESSION['persos']['pos_y'][$id];
$carte_pos      = $_SESSION['persos']['carte'][$id];

//Récupération du plan d'origine de la race
$plan_org = recup_camp_plan($camp) ;

if (isset($_SESSION['damier_persos']))
{
$liste_perso             = $_SESSION['damier_persos'];
}else {
	echo "<script language='javascript' type='text/javascript' >document.location='./$root_url/'</script>";exit;
	}
	
if (isset($_SESSION['damier_porte']))
{
$liste_porte             = $_SESSION['damier_porte'];
}
if (isset($_SESSION['damier_objet_complexe']))
{
$liste_objet_complexe     = $_SESSION['damier_objet_complexe'];
}
if (isset($_SESSION['damier_objet_simple']))
{
$liste_objet_simple     = $_SESSION['damier_objet_simple'];
}
if (isset($_SESSION['damier_bouclier']))
{
$liste_bouclier     = $_SESSION['damier_bouclier'];
}
if (isset($_SESSION['damier_artefact']))
{
$liste_artefact         = $_SESSION['damier_artefact'];
}


//Recherche des actions disponibles pour le perso./
// Grade = -2 pour les actions pouvant être réalisée par n'importe quel grade.
//$sql="SELECT * FROM action WHERE ((cercle_id=$cercle OR cercle_id=0) AND niv<=$niv_mag AND (grade=-2 OR grade=$grade) AND galon<=$galon) ORDER BY cercle_id, niv";
$sql="SELECT * FROM action LEFT JOIN grimoire ON (action.id = grimoire.id_sort)
	WHERE ((cercle_id=$cercle OR cercle_id=0) AND niv<=$niv_mag AND (grade=-2 OR grade=$grade) AND galon<=$galon) OR (grimoire.id_perso = $perso_id)
	ORDER BY id_perso, cercle_id, niv";
//echo $sql;
$rep=mysql_query($sql) or die(mysql_error());

$nb_act=0;
while($res=mysql_fetch_array($rep)){

	if($affil || $res['cercle_id']==0 || $res['cercle_id']>=6){
		//Tri sur les actions en fonction de la race
		//if($res['race'][$camp-1]==1) {
		//if(isset($res['id_perso'])) {
		$index = $camp-1;
		if($camp >= 5) {
			$index = 0;
		}
		
		if(($res['race'][$index]==1) || (isset($res['id_perso']))) {
			$nb_act++;
			$action[$nb_act]=$res;
			
			// Explode du nom, pour les sorts multi-nom
			$action[$nb_act]['nom'] = explose_nom_action($res['nom'], $index+1);
			
			// Ajout de la chance de réussir le sort (récuperé via la variable $chance)
			$chance = '';
			reussite_sort($grade, $action[$nb_act]['niv'], $caracs['niv_perception'], $chance);
			
			$action[$nb_act]['description'] .= ' (<i>chance de réussite: ~'.(ceil($chance/20)*2).'%</i>)';
			
			if($action[$nb_act]['zone']!=0 && $action[$nb_act]['cible']==0 && $action[$nb_act]['lanceur']!=2){
				$action[$nb_act]['type_action']=$action[$nb_act]['type_action'].'_zone';
				}
		}
	}
}

$nb_att=0;
$nb_ent=0;
$nb_spr=0;
$nb_sui=0;
$nb_rep=0;
$nb_sort=0;
$nb_sort_zone=0;
$nb_aura=0;
$nb_aura_zone=0;
$nb_divers=0;
//Classement des actions par type

for($inci=1; $inci<=$nb_act; $inci++){
	if($action[$inci]['type_action']=='attaque'){
		$nb_att++;
		$attaque['nom'][$nb_att]=$action[$inci]['nom'];
		$attaque['id'][$nb_att]=$inci;
		}
		elseif($action[$inci]['type_action']=='entrainement'){
				$nb_ent++;
				$entrainement['nom'][$nb_ent]=$action[$inci]['nom'];
				$entrainement['id'][$nb_ent]=$inci;
				}elseif($action[$inci]['type_action']=='reparation'){
				$nb_rep++;
				$reparation['nom'][$nb_rep]=$action[$inci]['nom'];
				$reparation['id'][$nb_rep]=$inci;
				}elseif($action[$inci]['type_action']=='suicide'){
				$nb_sui++;
				$suicide['nom'][$nb_sui]=$action[$inci]['nom'];
				$suicide['id'][$nb_sui]=$inci;
				}elseif($action[$inci]['type_action']=='sprint'){
				$nb_spr++;
				$sprint['nom'][$nb_spr]=$action[$inci]['nom'];
				$sprint['id'][$nb_spr]=$inci;
				}elseif($action[$inci]['type_action']=='sort'){
					$nb_sort++;
					$sort['nom'][$nb_sort]=$action[$inci]['nom'];
					$sort['id'][$nb_sort]=$inci;
					$sort['niv'][$nb_sort]=$action[$inci]['niv'];
					}elseif($action[$inci]['type_action']=='sort_zone'){
						$nb_sort_zone++;
						$sort_zone['nom'][$nb_sort_zone]=$action[$inci]['nom'];
						$sort_zone['id'][$nb_sort_zone]=$inci;
						$sort_zone['niv'][$nb_sort_zone]=$action[$inci]['niv'];
						}elseif($action[$inci]['type_action']=='aura'){
					$nb_aura++;
					$aura['nom'][$nb_aura]=$action[$inci]['nom'];
					$aura['id'][$nb_aura]=$inci;
					$aura['niv'][$nb_aura]=$action[$inci]['niv'];
					}elseif($action[$inci]['type_action']=='aura_zone'){
						$nb_aura_zone++;
						$aura_zone['nom'][$nb_aura_zone]=$action[$inci]['nom'];
						$aura_zone['id'][$nb_aura_zone]=$inci;
						$aura_zone['niv'][$nb_aura_zone]=$action[$inci]['niv'];
						}else{
							$nb_divers++;
							$divers['nom'][$nb_divers]=$action[$inci]['nom'];
							$divers['id'][$nb_divers]=$inci;
							}
	}
	
	
//Recherche des cibles potentielles
$nb_cibles=0;
for($inci=1 ; $inci<=$liste_perso['case']['inc'] ; $inci++){
        $nb_cibles++;
		$cible_pot['nom'][$nb_cibles]	=$liste_perso['case']['nom'][$inci];
        $cible_pot['id'][$nb_cibles]    =$liste_perso['case']['id'][$inci];
		$cible_pot['race'][$nb_cibles]  =$liste_perso['case']['race']['id'][$inci];
		if(recup_camp($cible_pot['race'][$nb_cibles])==$camp){
			$cible_pot['allie'][$nb_cibles]=true;
			}
			else {
				$cible_pot['allie'][$nb_cibles]=false;
				}
        $cible_pot['pos_x'][$nb_cibles] =$liste_perso['case']['pos_x'][$inci];
        $cible_pot['pos_y'][$nb_cibles] =$liste_perso['case']['pos_y'][$inci];
        $cible_pot['cac'][$nb_cibles]   =$liste_perso['case']['cible']['cac'][$inci];
        $cible_pot['map'][$nb_cibles]   =$liste_perso['case']['cible']['map'][$inci];
        $cible_pot['type'][$nb_cibles]  ='persos';
    }
for($inci=1 ; $inci<=$liste_porte['case']['inc'] ; $inci++){
		if($liste_porte['case']['destructible'][$inci]=="yes"){
			$nb_cibles++;
			$cible_pot['nom'][$nb_cibles]   =$liste_porte['case']['nom'][$inci];
			$cible_pot['id'][$nb_cibles]    =$liste_porte['case']['id'][$inci];
			$cible_pot['pos_x'][$nb_cibles] =$liste_porte['case']['pos_x'][$inci];
			$cible_pot['pos_y'][$nb_cibles] =$liste_porte['case']['pos_y'][$inci];
			$cible_pot['cac'][$nb_cibles]	=$liste_porte['case']['cible']['cac'][$inci];
			if($liste_porte['case']['type'][$inci]=='PorteMauve_parias'){
				$cible_pot['type'][$nb_cibles]='porte_mauve';
				$liste_porte['case']['type'][$inci]='porte_mauve';
				}else {
					$cible_pot['type'][$nb_cibles]	='porte';		
					$liste_porte['case']['type'][$inci]			='porte';
					}
			}

    }

for($inci=1 ; $inci<=$liste_objet_complexe['case']['inc'] ; $inci++){
	if($liste_objet_complexe['case']['destructible'][$inci]=="yes"){
        $nb_cibles++;
        $cible_pot['nom'][$nb_cibles]	=$liste_objet_complexe['case']['nom'][$inci];
        $cible_pot['id'][$nb_cibles]    =$liste_objet_complexe['case']['id'][$inci];
        $cible_pot['pos_x'][$nb_cibles] =$liste_objet_complexe['case']['pos_x'][$inci];
        $cible_pot['pos_y'][$nb_cibles] =$liste_objet_complexe['case']['pos_y'][$inci];
        $cible_pot['cac'][$nb_cibles]   =$liste_objet_complexe['case']['cible']['cac'][$inci];
        $cible_pot['type'][$nb_cibles]  ='objet_complexe';
		}
    }

for($inci=1 ; $inci<=$liste_objet_simple['case']['inc'] ; $inci++){
	if($liste_objet_simple['case']['destructible'][$inci]=="yes"){
        $nb_cibles++;
        $cible_pot['nom'][$nb_cibles]  	=$liste_objet_simple['case']['nom'][$inci];
        $cible_pot['id'][$nb_cibles]    =$liste_objet_simple['case']['id'][$inci];
        $cible_pot['pos_x'][$nb_cibles] =$liste_objet_simple['case']['pos_x'][$inci];
        $cible_pot['pos_y'][$nb_cibles] =$liste_objet_simple['case']['pos_y'][$inci];
        $cible_pot['cac'][$nb_cibles]   =$liste_objet_simple['case']['cible']['cac'][$inci];
        $cible_pot['type'][$nb_cibles]  ='objet_simple';
		}
    }

for($inci=1 ; $inci<=$liste_bouclier['case']['inc'] ; $inci++){
	if($liste_bouclier['case']['destructible'][$inci]=="yes"){
        $nb_cibles++;
        $cible_pot['nom'][$nb_cibles]   =$liste_bouclier['case']['nom'][$inci];
        $cible_pot['id'][$nb_cibles]    =$liste_bouclier['case']['id'][$inci];
        $cible_pot['pos_x'][$nb_cibles] =$liste_bouclier['case']['pos_x'][$inci];
        $cible_pot['pos_y'][$nb_cibles] =$liste_bouclier['case']['pos_y'][$inci];
        $cible_pot['cac'][$nb_cibles]   =$liste_bouclier['case']['cible']['cac'][$inci];
        $cible_pot['type'][$nb_cibles]	=$liste_bouclier['case']['nom_image'][$inci];
		}
    }
//Recherche des cibles non incarnées potentielles
//Condition mise sur la possession du sort



//Regroupement par action
//Pour l'attaque au cac :
//Persos
$cible['cac']=NULL;
$cible['reparation']=NULL;
$nb_cib_att=0;
$nb_cib_rep=0;

for($inci=1 ; $inci<=$liste_perso['case']['inc'] ; $inci++){
     if(($liste_perso['case']['cible']['cac'][$inci]!="None") && $liste_perso['case']['id'][$inci]!= $_SESSION['persos']['current_id']){
        $nb_cib_att++;
		$liste_perso['case']['cible']['cac'][$inci];
        $cible['cac']['nom'][$nb_cib_att]=$liste_perso['case']['nom'][$inci];
        $cible['cac']['pos'][$nb_cib_att]=search_pos($cible_pot, $liste_perso['case']['id'][$inci], 'persos');
        }
    }

//Objets
if($liste_porte['case']['inc']>0){
        $porte_id=0;
        for($inci=1 ; $inci<=$liste_porte['case']['inc'] ; $inci++){
            if($liste_porte['case']['cible']['cac'][$inci]!="None" && $liste_porte['case']['id'][$inci]!=$porte_id && $liste_porte['case']['destructible'][$inci]=="yes"){
                $porte_id=$liste_porte['case']['id'][$inci];
                $nb_cib_att++;
                $nb_cib_rep++;
                $cible['cac']['nom'][$nb_cib_att]=$liste_porte['case']['nom'][$inci];
                $cible['cac']['pos'][$nb_cib_att]=search_pos($cible_pot, $liste_porte['case']['id'][$inci], $liste_porte['case']['type'][$inci]);
                $cible['reparation']['nom'][$nb_cib_rep]=$liste_porte['case']['nom'][$inci];
                $cible['reparation']['pos'][$nb_cib_rep]=search_pos($cible_pot, $liste_porte['case']['id'][$inci], $liste_porte['case']['type'][$inci]);
                }
        }
    }

if($liste_bouclier['case']['inc']>0){
        $bouc_id=0;
        for($inci=1 ; $inci<=$liste_bouclier['case']['inc'] ; $inci++){
            if($liste_bouclier['case']['cible']['cac'][$inci]!="None" && $liste_bouclier['case']['id'][$inci]!=$bouc_id && $liste_bouclier['case']['destructible'][$inci]=="yes"){
                $bouc_id=$liste_bouclier['case']['id'][$inci];
                $nb_cib_att++;
                $nb_cib_rep++;
                $cible['cac']['nom'][$nb_cib_att]=$liste_bouclier['case']['nom'][$inci];
                $cible['cac']['pos'][$nb_cib_att]=search_pos($cible_pot, $liste_bouclier['case']['id'][$inci], $liste_bouclier['case']['nom_image'][$inci]);
                $cible['reparation']['nom'][$nb_cib_rep]=$liste_bouclier['case']['nom'][$inci];
                $cible['reparation']['pos'][$nb_cib_rep]=search_pos($cible_pot, $liste_bouclier['case']['id'][$inci], $liste_bouclier['case']['nom_image'][$inci]);
                }
        }
    }

if($liste_objet_complexe['case']['inc']>0){
        $obj_id=0;
        for($inci=1 ; $inci<=$liste_objet_complexe['case']['inc'] ; $inci++){
            if($liste_objet_complexe['case']['cible']['cac'][$inci]!="None" && $liste_objet_complexe['case']['id'][$inci]!=$obj_id && $liste_objet_complexe['case']['destructible'][$inci]=="yes"){
                $obj_id=$liste_objet_complexe['case']['id'][$inci];
                $nb_cib_att++;
                $nb_cib_rep++;
                $cible['cac']['nom'][$nb_cib_att]=$liste_objet_complexe['case']['nom'][$inci]." (Mat.".$liste_objet_complexe['case']['id'][$inci].")";
                $cible['cac']['pos'][$nb_cib_att]=search_pos($cible_pot, $liste_objet_complexe['case']['id'][$inci], 'objet_complexe');
                $cible['reparation']['nom'][$nb_cib_rep]=$liste_objet_complexe['case']['nom'][$inci]." (Mat.".$liste_objet_complexe['case']['id'][$inci].")";
                $cible['reparation']['pos'][$nb_cib_rep]=search_pos($cible_pot, $liste_objet_complexe['case']['id'][$inci], 'objet_complexe');
                }
        }
    }

if($liste_objet_simple['case']['inc']>0){
        $obj_id=0;
        for($inci=1 ; $inci<=$liste_objet_simple['case']['inc'] ; $inci++){
            if($liste_objet_simple['case']['cible']['cac'][$inci]!="None" && $liste_objet_simple['case']['id'][$inci]!=$obj_id && $liste_objet_simple['case']['destructible'][$inci]=="yes"){
                $obj_id=$liste_objet_simple['case']['id'][$inci];
                $nb_cib_att++;
                $nb_cib_rep++;
                $cible['cac']['nom'][$nb_cib_att]=$liste_objet_simple['case']['nom'][$inci]." (Mat.".$liste_objet_simple['case']['id'][$inci].")";
				$cible['cac']['pos'][$nb_cib_att]=search_pos($cible_pot, $liste_objet_simple['case']['id'][$inci], 'objet_simple');
				$cible['reparation']['nom'][$nb_cib_rep]=$liste_objet_simple['case']['nom'][$inci]." (Mat.".$liste_objet_simple['case']['id'][$inci].")";
				$cible['reparation']['pos'][$nb_cib_rep]=search_pos($cible_pot, $liste_objet_simple['case']['id'][$inci], 'objet_simple');
				}
        }
    }

//Pour l'entrainement :
//Persos uniquement
$cible['entrainement']=NULL;
$nb_cib_ent=0;
for($inci=1 ; $inci<=$liste_perso['case']['inc'] ; $inci++){
     if($liste_perso['case']['cible']['cac'][$inci]=="Allier"){
        $nb_cib_ent++;
		$liste_perso['case']['cible']['cac'][$inci];
		$cible['entrainement']['nom'][$nb_cib_ent]=$liste_perso['case']['nom'][$inci];
        $cible['entrainement']['pos'][$nb_cib_ent]=search_pos($cible_pot, $liste_perso['case']['id'][$inci], 'persos');
        }
    }


//Pour les sorts sur cible :
$nb_cib_sort=0;
$nb_cib_map=0;
$nb_cib_map_nolanceur=0;
$nb_cib_allie = 0;
$nb_cib_allie_nolanceur = 0;
$nb_cib_ennemi = 0;
$nb_cib_autre = 0;
$mage	= false;
for($inci=1 ; $inci<=$liste_perso['case']['inc'] ; $inci++){
        $nb_cib_map++;
		$pos = search_pos($cible_pot, $liste_perso['case']['id'][$inci], 'persos');
		if(recup_camp($liste_perso['case']['race']['id'][$inci]) == $camp){
			$nb_cib_allie++;
			$cible['allie']['nom'][$nb_cib_allie]	= $liste_perso['case']['nom'][$inci];
			$cible['allie']['pos'][$nb_cib_allie]	= $pos;
			if($liste_perso['case']['id'][$inci]!=$perso_id){
				$nb_cib_allie_nolanceur++;
				$cible['allie_nolanceur']['nom'][$nb_cib_allie_nolanceur]	= $liste_perso['case']['nom'][$inci];
				$cible['allie_nolanceur']['pos'][$nb_cib_allie_nolanceur]	= $pos;
				}
			}else {
				$nb_cib_ennemi++;
				$cible['ennemi']['nom'][$nb_cib_ennemi] = $liste_perso['case']['nom'][$inci];
				$cible['ennemi']['pos'][$nb_cib_ennemi]	= $pos;
				}
		$cible['map']['nom'][$nb_cib_map]	= $liste_perso['case']['nom'][$inci];
        $cible['map']['pos'][$nb_cib_map]	= $pos;
		if($liste_perso['case']['id'][$inci]!=$perso_id){
				$nb_cib_map_nolanceur++;
				$cible['map_nolanceur']['nom'][$nb_cib_map_nolanceur]	= $liste_perso['case']['nom'][$inci];
				$cible['map_nolanceur']['pos'][$nb_cib_map_nolanceur]	= $pos;
				}
    }

//Objets
if($liste_porte['case']['inc']>0){
        $porte_id=0;
        for($inci=1 ; $inci<=$liste_porte['case']['inc'] ; $inci++){
            if($liste_porte['case']['id'][$inci]!=$porte_id && $liste_porte['case']['destructible'][$inci]=="yes"){
                $porte_id=$liste_porte['case']['id'][$inci];
                $nb_cib_map++;
                $nb_cib_map_nolanceur++;
                $nb_cib_autre++;
				$pos = search_pos($cible_pot, $liste_porte['case']['id'][$inci], $liste_porte['case']['type'][$inci]);
                $cible['map']['nom'][$nb_cib_map]=$liste_porte['case']['nom'][$inci];
                $cible['map']['pos'][$nb_cib_map]= $pos;
				$cible['map_nolanceur']['nom'][$nb_cib_map_nolanceur]=$liste_porte['case']['nom'][$inci];
                $cible['map_nolanceur']['pos'][$nb_cib_map_nolanceur]= $pos;
				$cible['autre']['nom'][$nb_cib_autre] = $liste_porte['case']['nom'][$inci];
				$cible['autre']['pos'][$nb_cib_autre] = $pos;
                }
        }
    }

if($liste_bouclier['case']['inc']>0){
        $bouc_id=0;
        for($inci=1 ; $inci<=$liste_bouclier['case']['inc'] ; $inci++){
            if($liste_bouclier['case']['id'][$inci]!=$bouc_id && $liste_bouclier['case']['destructible'][$inci]=="yes"){
                $bouc_id=$liste_bouclier['case']['id'][$inci];
                $nb_cib_map++;
                $nb_cib_map_nolanceur++;
                $nb_cib_autre++;
				$pos = search_pos($cible_pot, $liste_bouclier['case']['id'][$inci], $liste_bouclier['case']['nom_image'][$inci]);
                $cible['map']['nom'][$nb_cib_map]= $liste_bouclier['case']['nom'][$inci];
                $cible['map']['pos'][$nb_cib_map]= $pos;
				$cible['map_nolanceur']['nom'][$nb_cib_map_nolanceur]= $liste_bouclier['case']['nom'][$inci];
                $cible['map_nolanceur']['pos'][$nb_cib_map_nolanceur]= $pos;
				$cible['autre']['nom'][$nb_cib_autre] = $liste_bouclier['case']['nom'][$inci];
				$cible['autre']['pos'][$nb_cib_autre] = $pos;
                }
        }
    }

if($liste_objet_complexe['case']['inc']>0){
        $obj_id=0;
        for($inci=1 ; $inci<=$liste_objet_complexe['case']['inc'] ; $inci++){
            if($liste_objet_complexe['case']['id'][$inci]!=$obj_id && $liste_objet_complexe['case']['destructible'][$inci]=="yes"){
                $obj_id=$liste_objet_complexe['case']['id'][$inci];
                $nb_cib_map++;
                $nb_cib_map_nolanceur++;
                $nb_cib_autre++;
				$pos = search_pos($cible_pot, $liste_objet_complexe['case']['id'][$inci], 'objet_complexe');
                $cible['map']['nom'][$nb_cib_map]= $liste_objet_complexe['case']['nom'][$inci]." (Mat.".$liste_objet_complexe['case']['id'][$inci].")";
                $cible['map']['pos'][$nb_cib_map]= $pos;
				$cible['map_nolanceur']['nom'][$nb_cib_map_nolanceur]= $liste_objet_complexe['case']['nom'][$inci]." (Mat.".$liste_objet_complexe['case']['id'][$inci].")";
                $cible['map_nolanceur']['pos'][$nb_cib_map_nolanceur]= $pos;
				$cible['autre']['nom'][$nb_cib_autre] = $liste_objet_complexe['case']['nom'][$inci]." (Mat.".$liste_objet_complexe['case']['id'][$inci].")";
				$cible['autre']['pos'][$nb_cib_autre] = $pos;
                }
        }
    }

if($liste_objet_simple['case']['inc']>0){
        $obj_id=0;
        for($inci=1 ; $inci<=$liste_objet_simple['case']['inc'] ; $inci++){
            if($liste_objet_simple['case']['id'][$inci]!=$obj_id && $liste_objet_simple['case']['destructible'][$inci]=="yes"){
                $obj_id=$liste_objet_simple['case']['id'][$inci];
                $nb_cib_map++;
                $nb_cib_map_nolanceur++;
                $nb_cib_autre++;
				$pos = search_pos($cible_pot, $liste_objet_simple['case']['id'][$inci], 'objet_simple'); 
                $cible['map']['nom'][$nb_cib_map]=$liste_objet_simple['case']['nom'][$inci]." (Mat.".$liste_objet_simple['case']['id'][$inci].")";
                $cible['map']['pos'][$nb_cib_map]= $pos;
				$cible['map_nolanceur']['nom'][$nb_cib_map_nolanceur]=$liste_objet_simple['case']['nom'][$inci]." (Mat.".$liste_objet_simple['case']['id'][$inci].")";
                $cible['map_nolanceur']['pos'][$nb_cib_map_nolanceur]= $pos;
				$cible['autre']['nom'][$nb_cib_autre] = $liste_objet_simple['case']['nom'][$inci]." (Mat.".$liste_objet_simple['case']['id'][$inci].")";
				$cible['autre']['pos'][$nb_cib_autre] = $pos;
                }
        }
    }
?>

<script language="javascript">


//const boolean porte=false;

function show_SubButton(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("sbt").style.display = "none";
    }
    else{
            document.getElementById("sbt").style.display = "";
        }

    }
</script>
<script language="javascript">

function show_Choice1(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("spn_1").style.display = "none";
    }
    else{
            document.getElementById("spn_1").style.display = "";
    }

    }

</script>
<script language="javascript">

function show_Choice2(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("spn_2").style.display = "none";
    }
    else{
            document.getElementById("spn_2").style.display = "";
    }

    }
</script>
<script language="javascript">

function show_Choice3(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("spn_3").style.display = "none";
            document.getElementById("spn_3").disabled = true;
    }
    else{
            document.getElementById("spn_3").style.display = "";
            document.getElementById("spn_3").disabled = false;
    }

    }
</script>
<script language="javascript">

function show_Choice4(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("spn_4").style.display = "none";
            document.getElementById("spn_4").disabled = true;
    }
    else{
            document.getElementById("spn_4").style.display = "";
            document.getElementById("spn_4").disabled = false;
    }

    }
</script>
<script language="javascript">

function show_Choice5(Is_Checked){
    if (Is_Checked == "N"){
            document.getElementById("spn_5").style.display = "none";
            document.getElementById("spn_5").disabled = true;
    }
    else{
            document.getElementById("spn_5").style.display = "";
            document.getElementById("spn_5").disabled = false;
    }

    }
</script>
<script language="javascript">

function do_action(id, ActionID, Cible1ID, Cible1Type, Cible1Nom, Cible1_X, Cible1_Y, Cible2ID, Cible2Type, Cible1allie, Cible2allie, Cible2nom, choix, X, Y){
document.getElementById("sbt1").disabled = true;
xhr = action(id, ActionID.value, Cible1ID.value, Cible1Type.value, Cible1Nom.value, Cible1_X.value, Cible1_Y.value, Cible2ID.value, Cible2Type.value, Cible1allie.value, Cible2allie.value, Cible2nom.value, choix.checked, X.value, Y.value);
}

function do_att(id, ActionID, Cible1ID, Cible1Type, Cible1Nom){
xhr = action(id, ActionID, Cible1ID, Cible1Type, Cible1Nom, '','','','','','','','','','');
}

function ChangeXY (X, Y, Cible1_X, Cible1_Y, Cible1Type, SubAct){
	Cible1Type.value='none';
	Cible1_X.value = X;
	Cible1_Y.value = Y;
	if(Cible1_X.value!="Abs" && Cible1_Y.value!="Ord"){
		SubAct.disabled=false;
		}
	}
function changealen(choixallie, choixallie1){
choixallie.checked=false;
choixallie1.checked=false;
}

function changeallie(value, Cible1allie, Cible2allie, choix, choixallie1){

	Cible1allie.value=value;
	Cible2allie.value=value;
	choix.checked=false;
	choixallie1.checked=false;
	}
	
function changeCible(val, Cib1, Cible1_X, Cib2, Actid, SubAct, Cib1type, Cib2type, Cib1id, Cible1Nom)
{
	document.getElementById("optGrAl1").style.display="none";
	document.getElementById("optGrAu1").style.display="none";
	document.getElementById("optGrEn1").style.display="none";
    document.getElementById("optGrAl2").style.display="none";
	document.getElementById("optGrAu2").style.display="none";
	document.getElementById("optGrEn2").style.display="none";
	changeallie(1, document.getElementById("Cible1allie"), document.getElementById("Cible2allie"), document.getElementById("choix"), document.getElementById("choixallie1"));
    changeallie(0, document.getElementById("Cible1allie"), document.getElementById("Cible2allie"), document.getElementById("choix"), document.getElementById("choixallie"));
    switch(val){
    <?php
	
    for($val=1; $val<=$nb_act; $val++){
	$effets = explode(':',$action[$val]['id_effet']); //Liste des effets
	$effets_lanceur = explode(',',$effets[0]);
	$effet_cible	= explode(',',$effets[1]);
		echo "case \"".$val."\" : 
		Cible1_X.disabled=true;
		Cib2type.value='';
        show_Choice1('Y');
		show_Choice4('N');
        show_Choice5('N');
		";
		$type_cible = $action[$val]['type_cible'];
		$cibles = array();
        if($action[$val]['type_action']=='attaque'){
            $nb = $nb_cib_att;
			$nb2=0;
            $cibles = $cible['cac'];
			$cibles2=$cibles;
			$sort_visible = "none";
			$sort_val = "";
			$subact='Attaquer';
            }
            elseif(($action[$val]['type_action']=='sort' || $action[$val]['type_action']=='aura') && $action[$val]['cible']!=-2){
			switch($type_cible){
				case 'allie':
					if($action[$val]['cible']==2 || $action[$val]['cible']==-1){
						echo "Cib2.disabled = false;
                                                        show_Choice2('Y');
                                                        ";
						}
						else{
							echo "Cib2.disabled = true;
							show_Choice2('N');
							";
							}
					$nb = 0;
					$nb2 = 0;
					$effets = explode(':',$action[$val]['id_effet']); //Liste des effets
					$effets_lanceur = explode(',',$effets[0]);
					$effet_cible	= explode(',',$effets[1]);
					if($effet_cible[0]!=0 && $action[$val]['lanceur']){
						if(isset($cible['allie']) && is_array($cible['allie'])) 
							{
								$nb += $nb_cib_allie ;
								$cibles = $cible['allie'];
								if(isset($cible['autre']) && is_array($cible['autre'])){
									$nb += $nb_cib_autre;
									$cibles = array_merge_recursive($cibles, $cible['autre']);
									}
							}
							elseif($nb_cib_autre) {
								$nb += $nb_cib_autre;
								$cibles = $cible['autre'];
								}
						} elseif($effet_cible[0]!=0){
									if(isset($cible['allie_nolanceur']) && is_array($cible['allie_nolanceur'])) 
										{
											$nb += $nb_cib_allie_nolanceur;
											$cibles = $cible['allie_nolanceur'];
											if(isset($cible['autre']) && is_array($cible['autre'])){
												$nb += $nb_cib_autre;
												$cibles = array_merge_recursive($cibles, $cible['autre']);
												}
										}
										elseif($nb_cib_autre) {
											$nb += $nb_cib_autre;
											$cibles = $cible['autre'];
											}
									}
									elseif($effet_cible[0]==0) {
										$nb=1;
										$mage=true;
										$cibles['nom'][1]= $nom;
										$cibles['pos'][1]= search_pos($cible_pot, $perso_id, 'persos');
										}
					if($action[$val]['cible']==-1){
						if($is_spawn){
							//recherche des portes du plan d'origine si elles existent
							$portes=rechch_id_porte($plan_org);
							$nb2=$portes[0];
							for($inc=1 ; $inc<=$portes[0] ; $inc++){
								$cibles2['nom'][$inc]= rechch_nom_porte($portes[$inc]);
								$cibles2['pos'][$inc]= $portes[$inc];
								echo "Cib2type.value='porte';";
								}
							}						
						}
						else{
							$cibles2=$cibles;
							$nb2=$nb;
							}
					break;
				case 'ennemi':
					$nb = 0;
					$nb2 = 0;
					if($action[$val]['cible']==2){
						echo "Cib2.disabled = false;
                                                        show_Choice2('Y');
                                                        ";
						}
						else{
							echo "Cib2.disabled = true;
							show_Choice2('N');
							";
							}
					$nb = $nb_cib_ennemi + $nb_cib_autre;
					if($nb>0){
						if(isset($cible['ennemi']) && is_array($cible['ennemi'])) 
							{
							if(isset($cible['autre']) && is_array($cible['autre'])){
								$cibles = $cible['autre'];
								}
							$cibles = array_merge_recursive($cibles, $cible['ennemi']);
							}
							elseif($nb_cib_autre) {
								$cibles = $cible['autre'];
								}
						}
					$cibles2=$cibles;
					$nb2=$nb;
					break;
				case 'choix' :
				case 'both' :
					if($action[$val]['cible']==2){
						echo "Cib2.disabled = false;
                                                        show_Choice2('Y');
                                                        ";
						}
						else{
							echo "Cib2.disabled = true;
							show_Choice2('N');
							";
							}
					$nb = 0;
					$nb2 = 0;
					$effets = explode(':',$action[$val]['id_effet']); //Liste des effets
					$effets_lanceur = explode(',',$effets[0]);
					$effet_cible	= explode(',',$effets[1]);
					if($effet_cible[0]!=0 && $action[$val]['lanceur']){
						if(isset($cible['allie']) && is_array($cible['allie'])) 
							{
								$nb += $nb_cib_allie ;
								$cibles = $cible['allie'];
								if(isset($cible['autre']) && is_array($cible['autre'])){
									$nb += $nb_cib_autre;
									$cibles = array_merge_recursive($cible['allie'], $cible['autre']);
									}
							}
							elseif($nb_cib_autre) {
								$nb += $nb_cib_autre;
								$cibles = $cible['autre'];
								}
						} elseif($effet_cible[0]!=0){
							if(isset($cible['allie_nolanceur']) && is_array($cible['allie_nolanceur'])) 
								{
								$nb += $nb_cib_allie_nolanceur;
								$cibles = $cible['allie_nolanceur'];
								if(isset($cible['autre']) && is_array($cible['autre'])){
									$nb += $nb_cib_autre;
									$cibles = array_merge_recursive($cible['allie_nolanceur'], $cible['autre']);
									}
								}
								elseif($nb_cib_autre) {
									$nb += $nb_cib_autre;
									$cibles = $cible['autre'];
									}
							}
							elseif($effet_cible[0]==0) {
								$nb=1;
								$cibles['nom'][1]= $nom;
								$cibles['pos'][1]= search_pos($cible_pot, $perso_id, 'persos');
								}
					$nb += $nb_cib_ennemi;
					if($nb>0){
						if(isset($cible['ennemi']) && is_array($cible['ennemi'])) 
							{
							$cibles = array_merge_recursive($cibles, $cible['ennemi']);
							}
						}
					$cibles2=$cibles;
					$nb2=$nb;
					break;
				case 'none' :
					$nb = 0;
					$nb2 = 0;
					echo "Cib2.disabled = false;
						Cible1_X.disabled=false;
						Cible1_X.value='';
						document.getElementById('Cible1_Y').value='';
                        show_Choice2('Y');
                                                ";
					if($action[$val]['zone']==-1){
						$carte_info 	= 	recup_carte_info($carte_pos);
						$x_min_info	=	$carte_info['x_min'];
						$x_max_info	=	$carte_info['x_max'];
						$y_min_info	=	$carte_info['y_min'];
						$y_max_info	=	$carte_info['y_max'];
						$circ_info	=	$carte_info['circ'];
						
						$perception = $caracs['perception'];
						$perception = 2*round($perception/2-0.5);
						for($inc=1;$inc<=$perception+1; $inc++){
							if($circ_info[0] && ($pos_x_perso-$perception/2+$inc-1)>$x_max_info){
								$cibles['nom'][$inc]="X : ".($pos_x_perso-$perception/2+$inc-1-($x_max_info-$x_min_info));
								$cibles['pos'][$inc]=($pos_x_perso-$perception/2+$inc-1-($x_max_info-$x_min_info));
								}
								elseif($circ_info[0] && ($pos_x_perso-$perception/2+$inc-1)<=$x_min_info){
										$cibles['nom'][$inc]="X : ".($pos_x_perso-$perception/2+$inc-1+($x_max_info-$x_min_info));
										$cibles['pos'][$inc]=($pos_x_perso-$perception/2+$inc-1+($x_max_info-$x_min_info));
										}
										else{
											$cibles['nom'][$inc]="X : ".($pos_x_perso-$perception/2+$inc-1);
											$cibles['pos'][$inc]=($pos_x_perso-$perception/2+$inc-1);
											}
							}	
						$nb = $perception+1;
						}elseif($action[$val]['zone']==-2){
							$perception=$caracs['perception'];
							for($inc=-$perception;$inc<=$perception; $inc++){
								if($circ_info[0] && ($pos_x_perso+$inc)>$x_max_info){
								$cibles['nom'][$inc+$perception+1]="X : ".($pos_x_perso+$inc-($x_max_info-$x_min_info));
								$cibles['pos'][$inc+$perception+1]=($pos_x_perso+$inc-($x_max_info-$x_min_info));
								}
								elseif($circ_info[0] && ($pos_x_perso+$inc)<=$x_min_info){
										$cibles['nom'][$inc+$perception+1]="X : ".($pos_x_perso+$inc+($x_max_info-$x_min_info));
										$cibles['pos'][$inc+$perception+1]=($pos_x_perso+$inc+($x_max_info-$x_min_info));
										}
										else{
											$cibles['nom'][$inc+$perception+1]="X : ".($pos_x_perso+$inc);
											$cibles['pos'][$inc+$perception+1]=($pos_x_perso+$inc);
											}
								}	
							$nb = 2*$perception+1;
							}elseif($action[$val]['zone']==-3){
									}	
					if($action[$val]['zone']==-1){
						$perception = $caracs['perception'];
						$perception = 2*round($perception/2-0.5);
						for($inc=1;$inc<=$perception+1; $inc++){
							$cibles2['nom'][$inc]="Y : ".($pos_y_perso-$perception/2+$inc-1);
							$cibles2['pos'][$inc]=($pos_y_perso-$perception/2+$inc-1);
							}	
						$nb2 = $perception+1;
						}elseif($action[$val]['zone']==-2){
							$perception=$caracs['perception'];
							for($inc=-$perception;$inc<=$perception; $inc++){
								$cibles2['nom'][$inc+$perception+1]="Y : ".($pos_y_perso+$inc);
								$cibles2['pos'][$inc+$perception+1]=($pos_y_perso+$inc);
								}	
							$nb2 = 2*$perception+1;
							}elseif($action[$val]['zone']==-3){
						echo "Cib2.disabled = true;
						show_Choice1('N');
                                                show_Choice2('N');
						show_Choice4('Y');
                                                ";
									}
								
					break;
				default :
					echo "Cib2.disabled = true;
						show_Choice2('N');
						";
					$nb = 0;
					$nb2 = 0;
					$effets = explode(':',$action[$val]['id_effet']); //Liste des effets
					$effets_lanceur = explode(',',$effets[0]);
					$effet_cible	= explode(',',$effets[1]);
					if($effet_cible[0]!=0 && $action[$val]['lanceur']){
						$nb = $nb_cib_map;
						$cibles = $cible['map'];
						} elseif($effet_cible[0]!=0 && $nb_cib_map_nolanceur){
							$nb = $nb_cib_map_nolanceur;
							$cibles = $cible['map_nolanceur'];	
							}
					$cibles2=$cibles;
					$nb2=$nb;
					break;
					}
                $subact='Lancer le sort';
				$sort_visible = "";
				$sort_val = $action[$val]['description'];
                }elseif($action[$val]['type_action']=='entrainement'){
                    $nb = $nb_cib_ent;
					$nb2=0;
                    $cibles = $cible['entrainement'];
                    $subact="S\'entrainer";
					$sort_visible = "none";
					$sort_val = "";
					$cibles2=$cibles;
					$nb2=$nb;
                    }elseif($action[$val]['type_action']=='suicide'){
                    $subact="Se suicider";
					$sort_visible = "none";
					$sort_val = "";
                    }elseif($action[$val]['type_action']=='sprint'){
                    $subact="Piquer un sprint";
					$sort_visible = "none";
					$sort_val = "";
                    }elseif($action[$val]['type_action']=='reparation'){
                     $nb = $nb_cib_rep;
					$nb2=0;
                    $cibles = $cible['reparation'];
                    $subact="Réparer";
					$sort_visible = "none";
					$sort_val = "";
					$cibles2=$cibles;
					$nb2=$nb;
                    }

        if($action[$val]['type_action']=='sort_zone' || $action[$val]['type_action']=='aura_zone'){
			$id_temp=search_pos($cible_pot, $perso_id, 'persos');
            echo "show_Choice1('N');
            show_Choice2('N');
            show_Choice5('Y');
            Cib1id.value='".$perso_id."';
            Cible1Nom.value='".str_replace("'", "\'",$cible_pot['nom'][$id_temp])."';
            Cib1type.value='persos';
            Cib1.disabled = true;
            Cib2.disabled = true;
			document.getElementById('Cible1allie').value=0;
			document.getElementById('Cible2allie').value=0;
            Actid.value=".$action[$val]['id'].";
			SubAct.disabled=false;
			";
			if($type_cible=='choix'){
				echo "show_Choice3('Y');
				";
				}else echo "show_Choice3('N');
				";
            $subact='Lancer le sort';
			$sort_visible = "";
			$sort_val = $action[$val]['description'];
            }elseif(($effet_cible[0]==0 || $action[$val]['cible']==-2) && $action[$val]['lanceur']!=2){
			if(!isset($cible_pot)){
				$f=fopen("reportbug_panel_action",'a');
				$string .= "\n";
				$string = print_r($liste_perso, true);
				$string .= "\n";
				$string .= $perso_id;
				fwrite($f,$string);
				fclose($f);
				}
			$id_temp=search_pos($cible_pot, $perso_id, 'persos');
			echo "show_Choice1('N');
            show_Choice2('N');
            show_Choice5('N');
            Cib1id.value='".$perso_id."';
            Cible1Nom.value='".str_replace("'", "\'",$cible_pot['nom'][$id_temp])."';
            Cib1type.value='persos';
            Cib1.disabled = true;
            Cib2.disabled = true;
			document.getElementById('Cible1allie').value=1;
			document.getElementById('Cible2allie').value=1;
            Actid.value=".$action[$val]['id'].";
			SubAct.disabled=false;
			";			
			}
            else{
                echo "SubAct.disabled=true;
				Cib1.length=0;
            ";
                echo "Cib2.length=0;
            ";
		
			echo "Cib1.options[0]=new Option('Sélectionnez une cible',0);
			      Cib2.options[0]=new Option('Sélectionnez une cible',0);
				Cib1.appendChild(document.getElementById('optGrAl1'));
				Cib1.appendChild(document.getElementById('optGrAu1'));
				Cib1.appendChild(document.getElementById('optGrEn1'));
				Cib2.appendChild(document.getElementById('optGrAl2'));
				Cib2.appendChild(document.getElementById('optGrAu2'));
				Cib2.appendChild(document.getElementById('optGrEn2'));
				";
                for ($inci=1 ; $inci<= $nb; $inci++){
				echo 'Cib1.options['.($inci).']=new Option("'.str_replace("\"", "'",$cibles['nom'][$inci]).'",'.$cibles['pos'][$inci].');
				' ;
				if ($inci<= $nb2){
				echo 'Cib2.options['.($inci).']=new Option("'.str_replace("\"", "'",$cibles2['nom'][$inci]).'",'.$cibles2['pos'][$inci].');
				' ;}
					if($type_cible=='choix' || ($type_cible=='both' && $action[$val]['type_action']!='attaque' && $action[$val]['type_action']!='entrainement')){
						if($inci<=($nb-$nb_cib_autre-$nb_cib_ennemi) && ($nb-$nb_cib_autre-$nb_cib_ennemi)){
							
							echo 'document.getElementById("optGrAl1").appendChild(Cib1.options['.($inci).']);
							document.getElementById("optGrAl1").style.display="";
							';
				if ($inci<= $nb2)
							echo 'document.getElementById("optGrAl2").appendChild(Cib2.options['.($inci).']);
							document.getElementById("optGrAl2").style.display="";
							';
							}
							elseif($inci<=($nb-$nb_cib_ennemi) && ($nb-$nb_cib_ennemi) ){
								
								echo 'document.getElementById("optGrAu1").appendChild(Cib1.options['.($inci).']);
								document.getElementById("optGrAu1").style.display="";
								';
				if ($inci<= $nb2)
								echo 'document.getElementById("optGrAu2").appendChild(Cib2.options['.($inci).']);
								document.getElementById("optGrAu2").style.display="";
								';
								}
								elseif($inci<=$nb && ($nb_cib_ennemi)){
									
									echo 'document.getElementById("optGrEn1").appendChild(Cib1.options['.($inci).']);
									document.getElementById("optGrEn1").style.display="";
									';	
				if ($inci<= $nb2)												
									echo 'document.getElementById("optGrEn2").appendChild(Cib2.options['.($inci).']);
									document.getElementById("optGrEn2").style.display="";
									';
									}									
								}
					if($type_cible=='allie'){
						if(($inci<=($nb-$nb_cib_autre) && ($nb-$nb_cib_autre))||$mage){		
							echo 'document.getElementById("optGrAl1").appendChild(Cib1.options['.($inci).']);
							document.getElementById("optGrAl1").style.display="";
							';
							$mage=false;
				if ($inci<= $nb2)
							echo 'document.getElementById("optGrAl2").appendChild(Cib2.options['.($inci).']);
							document.getElementById("optGrAl2").style.display="";
							';
							}
							elseif($inci<=$nb && ($nb_cib_autre)){
								echo 'document.getElementById("optGrAu1").appendChild(Cib1.options['.($inci).']);
								document.getElementById("optGrAu1").style.display="";
								';
				if ($inci<= $nb2)
								echo 'document.getElementById("optGrAu2").appendChild(Cib2.options['.($inci).']);
								document.getElementById("optGrAu2").style.display="";
								';
								}
						}
					if($type_cible=='ennemi'){
						if($inci<=($nb-$nb_cib_ennemi) && ($nb-$nb_cib_ennemi)){
							echo 'document.getElementById("optGrAu1").appendChild(Cib1.options['.($inci).']);
							document.getElementById("optGrAu1").style.display="";
							';
				if ($inci<= $nb2)
							echo 'document.getElementById("optGrAu2").appendChild(Cib2.options['.($inci).']);
							document.getElementById("optGrAu2").style.display="";
							';
							}
							elseif($inci<=$nb && ($nb_cib_ennemi)){
								echo 'document.getElementById("optGrEn1").appendChild(Cib1.options['.($inci).']);
								document.getElementById("optGrEn1").style.display="";
								';	
				if ($inci<= $nb2)												
								echo 'document.getElementById("optGrEn2").appendChild(Cib2.options['.($inci).']);
								document.getElementById("optGrEn2").style.display="";
								';													
								}
								
						}
					if ($action[$val]['type_action']=='attaque'){
						$id_temp=$cibles['pos'][$inci];
						if(isset($cible_pot['allie'][$id_temp]) && $cible_pot['allie'][$id_temp]==true){
							echo 'document.getElementById("optGrAl1").appendChild(Cib1.options['.($inci).']);
							document.getElementById("optGrAl1").style.display="";
							';
							}
							elseif(!isset($cible_pot['allie'][$id_temp])){
								echo 'document.getElementById("optGrAu1").appendChild(Cib1.options['.($inci).']);
								document.getElementById("optGrAu1").style.display="";
								';
									}
									elseif(isset($cible_pot['allie'][$id_temp]) && $cible_pot['allie'][$id_temp]==false){
										echo 'document.getElementById("optGrEn1").appendChild(Cib1.options['.($inci).']);
										document.getElementById("optGrEn1").style.display="";
									';
									}
						}
					if($action[$val]['type_action']=='entrainement'){
						echo 'document.getElementById("optGrAl1").appendChild(Cib1.options['.($inci).']);
						document.getElementById("optGrAl1").style.display="";
						';
						}
                    }
                echo "Cib1.disabled = false;
            Actid.value=".$action[$val]['id'].";
			Cib1id.value='';
            Cib1type.value='';
            ";
			if($type_cible=='choix' && $action[$val]['zone']!=0){
				echo "show_Choice3('Y');
				";
				}else echo "show_Choice3('N');
				";
                }
		$cout=$action[$val]['cout'];
		if(!$cout)
			$cout='Tous les';
        echo "SubAct.value='".$subact."';
        show_SubButton('Y');
		document.getElementById(\"info_sort\").style.display=\"$sort_visible\";
		document.getElementById(\"info_sort\").innerHTML=\"($cout PA) $sort_val\";
        break;
        ";

        }
    ?>	
    default :
		show_Choice1('N');
        show_Choice2('N');
        show_Choice3('N');
        show_SubButton('N');
		Cib1.disabled = true;
		Cible1_X.disabled=true;
        Cib2.disabled = true;
        SubAct.disabled=true;
		SubAct.value='Envoyer';
		Actid.value="";
		Cib1id.value="";
        Cib1type.value="";
		document.getElementById("info_sort").style.display="none";
		document.getElementById("info_sort").innerHTML="";
        break;
        }
    }
 

function attchangeCibleType(val, Cible1Type, Cible1Nom, Cible1ID, SubAct)
{
    switch(val){
    <?php
    for($val=1; $val<=$nb_cibles; $val++){
        echo "case \"".$val."\" :
		Cible1Type.value='".$cible_pot['type'][$val]."';
       	Cible1Nom.value='".str_replace("'", "\'",$cible_pot['nom'][$val])."';
       	Cible1ID.value=".$cible_pot['id'][$val].";
		SubAct.disabled=false;
		break;
		";
        }
    ?>
    default :
    Cible1Type.value='';
    Cible1Nom.value='';
    Cible1ID.value='';
	SubAct.disabled=true;
	SubAct.value='Envoyer';
        break;
        }
}   

</script>

<?php  ?>
<div id='form_att' style='display:""'>
<form name='form_att' method='get'>
<select name="attCible1" id="attCible1" style="width:250px;" onChange="javascript:attchangeCibleType(this.value, attCible1Type, attCible1Nom, attCible1ID, attSubmitAction);">
<option value="0">S&eacute;lectionnez une cible &agrave; attaquer</option> 
<?php
$nb = $nb_cib_att;
$cibles = $cible['cac'];

for($inci=1; $inci<=$nb; $inci++){
	$id_temp=$cibles['pos'][$inci];
	if(isset($cible_pot['allie'][$id_temp]) && $cible_pot['allie'][$id_temp]==true){
		echo "<option value=".$cibles['pos'][$inci].">".str_replace("\"", "'",$cibles['nom'][$inci])."</option>";
		}
	}

for($inci=1; $inci<=$nb; $inci++){
	$id_temp=$cibles['pos'][$inci];
	if(!isset($cible_pot['allie'][$id_temp])){
		echo "<option value=".$cibles['pos'][$inci].">".str_replace("\"", "'",$cibles['nom'][$inci])."</option>";
		}
	}

for($inci=1; $inci<=$nb; $inci++){
	$id_temp=$cibles['pos'][$inci];
	if(isset($cible_pot['allie'][$id_temp]) && $cible_pot['allie'][$id_temp]==false){
		echo "<option value=".$cibles['pos'][$inci].">".str_replace("\"", "'",$cibles['nom'][$inci])."</option>";
		}
	}
?>
</select>

<input type='hidden' name="attActionID" value='1'/>
<input type='hidden' name="attperso_id" value=''/>
<input type='hidden' name="attCible1ID" value=''/>
<input type='hidden' name="attCible1Type" value=''/>
<input type='hidden' name="attCible1Nom" value=''/><br/>
<input type='button' value='Attaquer' name="attSubmitAction" disabled onClick="javascript:do_att(<?php echo $id; ?>, attActionID.value, attCible1ID.value, attCible1Type.value, attCible1Nom.value);"/>
</form>
</div><?php  ?>
<br/>

<div id='form_actions' style='display:""'>
<form name='form_actions' method='get'>
<select name="actiontype" id="actiontype" style="width:250px;" onChange="javascript:changeCible(this.value, Cible1, Cible1_X, Cible2, ActionID, SubmitAction, Cible1Type, Cible2Type, Cible1ID, Cible1Nom);">
<option value="0">Choisir une action</option>
<?php
if($nb_att || $nb_ent){
	echo "<optgroup label='Actions de base'>";
	}
for($inci=1; $inci<=$nb_ent; $inci++){
	echo "<option value=".$entrainement['id'][$inci].">".$entrainement['nom'][$inci]."</option>";
	}
for($inci=1; $inci<=$nb_sui; $inci++){
	echo "<option value=".$suicide['id'][$inci].">".$suicide['nom'][$inci]."</option>";
	}
for($inci=1; $inci<=$nb_spr; $inci++){
	echo "<option value=".$sprint['id'][$inci].">".$sprint['nom'][$inci]."</option>";
	}
for($inci=1; $inci<=$nb_rep; $inci++){
	echo "<option value=".$reparation['id'][$inci].">".$reparation['nom'][$inci]."</option>";
	}
if($nb_att || $nb_ent){
	echo "</optgroup>";
	}


        /*     * ** Génération de la liste des sorts *** */

    if ($nb_sort || $nb_sort_zone) {
        echo "<optgroup label='Magie'>";

        // Compteurs initialisés au dernier sort car les sorts sont triés par ordre croissant et on doit les afficher en ordre décroissant pour plus d'ergonomie
        $cpt_sort = $nb_sort;
        $cpt_sort_zone = $nb_sort_zone;

        while ($cpt_sort || $cpt_sort_zone) {

            // Détermination du niveau de sort en cours pour n'afficher que les sorts de ce niveau
            $niveau = max(isset($sort['niv'][$cpt_sort]) ? $sort['niv'][$cpt_sort] : 0, isset($sort_zone['niv'][$cpt_sort_zone]) ? $sort['niv'][$cpt_sort_zone] : 0);

            echo "<optgroup label='Niveau " . $niveau . "'>";

            if (isset($sort['niv'][$cpt_sort]) && $sort['niv'][$cpt_sort] == $niveau) {
                while (isset($sort['niv'][$cpt_sort]) && $sort['niv'][$cpt_sort] == $niveau) {
                    echo "<option value=" . $sort['id'][$cpt_sort] . ">" . $sort['nom'][$cpt_sort] . " (cible)</option>";
                    $cpt_sort--;
                }
            }

            if (isset($sort_zone['niv'][$cpt_sort_zone]) && $sort_zone['niv'][$cpt_sort_zone] == $niveau) {
                while (isset($sort_zone['niv'][$cpt_sort_zone]) && $sort_zone['niv'][$cpt_sort_zone] == $niveau) {
                    echo "<option value=" . $sort_zone['id'][$cpt_sort_zone] . ">" . $sort_zone['nom'][$cpt_sort_zone] . " (zone)</option>";
                    $cpt_sort_zone--;
                }
            }
            echo "</optgroup>";
        }

        echo "</optgroup>";
    }
    /*     * ** Fin de la génération de la liste des sorts *** */

	
if($nb_aura){
	echo "<optgroup label='Auras cibl&eacute;s'>";
	}
for($inci=1; $inci<=$nb_aura; $inci++){
	echo "<option value=".$aura['id'][$inci].">".$aura['nom'][$inci]."</option>";
	}
if($nb_aura){
	echo "</optgroup>";
	}
	
if($nb_aura_zone){
	echo "<optgroup label='Auras'>";
	}
for($inci=1; $inci<=$nb_aura_zone; $inci++){
	echo "<option value=".$aura_zone['id'][$inci].">".$aura_zone['nom'][$inci]."</option>";
	}
if($nb_aura_zone){
	echo "</optgroup>";
	}

if($nb_divers){
	echo "<optgroup label='Divers'>";
	}
for($inci=1; $inci<=$nb_divers; $inci++){
	echo "<option value=".$divers['id'][$inci].">".$divers['nom'][$inci]."</option>";
	}
if($nb_divers){
	echo "</optgroup>";
	}
?>

</select>

<script language="javascript">
function changeCibleType(val, Cible1Type, Cible1Nom, Cible1ID, Cible1_X, Cible1_Y, SubAct)
{
 if(Cible1_X.disabled){
    switch(val){
    <?php
    for($val=1; $val<=$nb_cibles; $val++){
        echo "case \"".$val."\" :
		Cible1Type.value='".$cible_pot['type'][$val]."';
        	Cible1Nom.value='".str_replace("'", "\'",$cible_pot['nom'][$val])."';
        	Cible1ID.value=".$cible_pot['id'][$val].";
			";
			if($cible_pot['type'][$val]=='persos'){
				echo "document.getElementById('Cible1allie').value='".$cible_pot['allie'][$val]."';	
";				}
			echo "Cible1_X.value=".$cible_pot['pos_x'][$val]."; 
		Cible1_Y.value=".$cible_pot['pos_y'][$val]."; 
		if(document.getElementById('Cible2ID').value==''){
			SubAct.disabled=!document.getElementById('Cible2').disabled;
			}
			else {
					SubAct.disabled=false;
					}
		break;
		
		";
        }
    ?>		
	
    default :
        Cible1Type.value='';
        Cible1Nom.value='';
        Cible1ID.value='';
	Cible1_X.value='';
	Cible1_Y.value='';
	SubAct.disabled=true;
	SubAct.value='Envoyer';
        break;
        }
    }
   else {
	Cible1Type.value='none';
        Cible1Nom.value='';
       	Cible1ID.value=''; 
        Cible1_X.value=val; 
		document.getElementById('Cible2').disabled=false; 
		if(document.getElementById('Cible1_Y').value==''){
			SubAct.disabled=true;
			}
			else {
					SubAct.disabled=false;
					}
	}
}    

function changeCibleType1(val, CibleType, CibleNom, CibleID, Cible_X, Cible_Y, SubAct)
{
 if(Cible_X.disabled){
	if (CibleType.value!='porte'){
		switch(val){
		<?php
		for($val=1; $val<=$nb_cibles; $val++){
			echo "case \"".$val."\" :
			CibleType.value='".$cible_pot['type'][$val]."';
				CibleNom.value='".str_replace("'", "\'",$cible_pot['nom'][$val])."';
				CibleID.value=".$cible_pot['id'][$val].";
			";
			if($cible_pot['type'][$val]=='persos'){
				echo "document.getElementById('Cible2allie').value='".$cible_pot['allie'][$val]."';	
";				}
			echo "Cible_X.value=".$cible_pot['pos_x'][$val]."; 
			Cible_Y.value=".$cible_pot['pos_y'][$val]."; 

			SubAct.disabled=false;		
			break;
			
			";
			}
		?>	
		default :
			CibleType.value='';
			CibleNom.value='';
			CibleID.value='';
		Cible_X.value='';
		Cible_Y.value='';
		SubAct.disabled=true;
		SubAct.value='Envoyer';
			break;
			}
		}
		else {
				CibleNom.value='Porte';
				CibleID.value=val; 
				SubAct.disabled=false;	
			}
    }
   else {
        Cible_Y.value=val;
        if(document.getElementById('Cible1_X').value==''){
			SubAct.disabled=true;
			}
			else {
					SubAct.disabled=false;
					}
	}
}    
 

</script>
    
<div id="spn_1">
<select disabled name="Cible1" id="Cible1" style="width:250px;" onChange="javascript:changeCibleType(this.value, Cible1Type, Cible1Nom, Cible1ID, Cible1_X, Cible1_Y, SubmitAction);">
<option value="0">S&eacute;lectionnez une cible</option> 
<optgroup label="Allies" id="optGrAl1"></optgroup>
<optgroup label="Autres" id="optGrAu1"></optgroup>
<optgroup label="Ennemis" id="optGrEn1"></optgroup>
</select>
</div>

<div id="spn_2">
<select disabled name="Cible2" id="Cible2" style="width:250px;" onChange="javascript:changeCibleType1(this.value, Cible2Type, Cible2Nom, Cible2ID, Cible1_X, Cible1_Y, SubmitAction);">
<option value="0">S&eacute;lectionnez une cible</option> 
<optgroup label="Allies" id="optGrAl2"></optgroup>
<optgroup label="Autres" id="optGrAu2"></optgroup>
<optgroup label="Ennemis" id="optGrEn2"></optgroup>
</select>
</div>
 
<div id="spn_3">
Toucher alli&eacute;s et ennemis <input type="checkbox" name="choix" id="choix" onClick='javascript:changealen(choixallie, choixallie1);'/><br/>
</div>
<div id="spn_5">
Toucher alli&eacute;s uniquement <input type="checkbox" name="choixallie" id="choixallie" value='1' onClick='javascript:changeallie(this.value, Cible1allie, Cible2allie, choix, choixallie1);'/><br/>
Toucher ennemis uniquement <input type="checkbox" name="choixallie1" id="choixallie1" value='0' onClick='javascript:changeallie(this.value, Cible1allie, Cible2allie, choix, choixallie);'/><br/>
</div>
<div id="spn_4">
  <input type="text" name="X" value="Ord" id="X" onChange='javascript:ChangeXY(this.value, Y.value, Cible1_X, Cible1_Y, Cible1Type, SubmitAction);'/><br/>
  <input type="text" name="Y" value="Abs" id="Y" onChange='javascript:ChangeXY(X.value, this.value, Cible1_X, Cible1_Y, Cible1Type, SubmitAction);'/><br/>
</div>

<input type='hidden' name="ActionID" value=''/>
<input type='hidden' name="Cible1ID" value=''/>
<input type='hidden' name="Cible1Type" value=''/>
<input type='hidden' name="Cible1Nom" value=''/>
<input type='hidden' name="Cible1allie" id="Cible1allie" value=''/>
<input type='hidden' name="Cible1_X" id="Cible1_X" value=''/>
<input type='hidden' name="Cible1_Y" id="Cible1_Y" value=''/>
<input type='hidden' name="Cible2ID" id="Cible2ID" value=''/>
<input type='hidden' name="Cible2Type" id="Cible2Type" value=''/>
<input type='hidden' name="Cible2Nom" id="Cible2Nom" value=''/>
<input type='hidden' name="Cible2allie" id="Cible2allie" value=''/>
<div id="sbt"><input type='button' id="sbt1" name="SubmitAction" disabled onClick='javascript:do_action(<?php echo $id; ?>, ActionID, Cible1ID, Cible1Type, Cible1Nom, Cible1_X, Cible1_Y, Cible2ID, Cible2Type, Cible1allie, Cible2allie, Cible2Nom, choix, X, Y);'/></div>
</form>
<div class="sort_ok" style="display:none" id="info_sort"></div>
</div>
<script language="javascript">
show_SubButton('N');
show_Choice1('N');
show_Choice2('N');
show_Choice3('N');
show_Choice4('N');
show_Choice5('N');
</script>
<?php
}
?>
