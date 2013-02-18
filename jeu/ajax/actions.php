<?php

use persos\event\eventFormatter as eventFormatter;


require_once __DIR__ . "/../../conf/master.php";

include(SERVER_ROOT . "/persos/fonctions.php");
include(SERVER_ROOT . "/jeu/fonctions.php");


// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');
ControleAcces('utilisateur',1);

if(!isset($_SESSION['temp']['info_action'])){
	$_SESSION['temp']['info_action'] ='';
}


if(!isset($_GET['ActionID'])){
	$_SESSION['action']['erreur'] = "Cette action n'existe pas !:erreur";
	exit;
}

//Réinitialisation de variable de session
$_SESSION['gain_xp']['att']=NULL;
$_SESSION['gain_xp']['def']=NULL;
$_SESSION['gain_xp']['reparation']=NULL;
$_SESSION['demi_esquive']= false;
$_SESSION['temp']['teleportation']=false;
$_SESSION['esquive']['nb'] = 0;
$_SESSION['event_effect']['malus'] = 0 ;
$_SESSION['esquive']['somme_rang'] = 0;
$_SESSION['esquive']['table_rang'] = array();

//recup des infos perso
$perso_id     		= $_SESSION['persos']['current_id'];
$id            		= $_SESSION['persos']['id'][0];
activ_tour($id) ;
$pos_x_perso     	= $_SESSION['persos']['pos_x'][$id];
$pos_y_perso     	= $_SESSION['persos']['pos_y'][$id];
$carte_pos         	= $_SESSION['persos']['carte'][$id];
$race_perso    		= $_SESSION['persos']['race'][$id];
$grade_perso    	= $_SESSION['persos']['grade'][$id];
$camp_perso			= recup_camp($race_perso);
$caracs         	= calcul_caracs();
$caracs_max			= caracs_base_max($perso_id, $race_perso, $grade_perso);
$perso_carac_noalter	= recup_carac($perso_id, array('pa', 'mouv'));
$perception 		= $caracs['perception'];
$niv_mag 		= $caracs['niv'];
$cercle 		= $caracs['cercle'];
$percept_up		= $caracs['niv_perception'];

//vérification sur le fait que le personnageest toujours présent sur la carte
$sql="SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
$resultat = mysql_query ($sql) or die (mysql_error());
if(!($pos = mysql_fetch_array ($resultat))){
	$_SESSION['action']['erreur'] = "Vous &ecirc;tes mort !:erreur";
	exit;
}

//Recup des infos de l'action
$action_id 		= $_GET['ActionID'];

if(preg_match('#[a-z]#i',$action_id)){
	$_SESSION['action']['erreur'] = "Action interdite ! Compte banni pour une semaine.:erreur";
	//Ajouter un repport triche
	exit;
}

$sql        	="SELECT * FROM action LEFT JOIN grimoire ON (action.id = grimoire.id_sort) WHERE (cercle_id=$cercle OR cercle_id=0 OR id_perso IS NOT NULL) AND id='$action_id'";
$resultat    	= mysql_query($sql)or die (mysql_error());
$action_info    = mysql_fetch_array ($resultat);

// Récupération du vrai nom de l'action
$index = $camp_perso;
if($camp_perso >= 5){
	$index = 1;
}
$action_info['nom'] = explose_nom_action($action_info['nom'], $index);

if (!isset($action_info['type_action'])){
	$_SESSION['action']['erreur'] = "Vous n'&ecirc;tes pas autoris&eacute; &agrave; r&eacute;aliser cette action !:erreur";
	exit;
}
$cercle_action = $action_info['cercle_id'];

$sql 			= "SELECT superieur_id FROM persos WHERE id=$perso_id";
$resultat    	= mysql_query($sql)or die (mysql_error());
$resultat 		= mysql_fetch_array($resultat);
if(!($resultat['superieur_id']) && $action_info['type_action']=='sort' && $cercle_action!=0 && $cercle_action<6){
	$_SESSION['action']['erreur'] = "Vous n'&ecirc;tes pas affili&ecirc;, donc pas autoris&eacute; &agrave; r&eacute;aliser cette action !:erreur";
	exit;
}

$_SESSION['action']['type'] = $action_info['type_action'];
$_SESSION['action']['id'] = $action_id;

$cible_type  ='';

//Recup des infos de la cible
if(isset($_GET['Cible1ID'])){
	$cible_id    	= $_GET['Cible1ID'];
	$cible_type  	= $_GET['Cible1Type'];
	$cible_nom  	= $_GET['Cible1Nom'];
	$cible_isally	= $_GET['Cible1allie'];

	$cible["pos_x"][1] = $_GET['Cible1_X'];
	$cible["pos_y"][1] = $_GET['Cible1_Y'];

	if(preg_match('#[a-z]#i',$cible_id)){
		$_SESSION['action']['erreur'] = "Action interdite ! Compte banni pour une semaine.:erreur";
		//Ajouter un repport triche
		exit;
	}
	if(preg_match('#[a-z]#i',$cible["pos_x"][1])){
		echo "erreur";exit;
	}
	if(preg_match('#[a-z]#i',$cible["pos_y"][1])){
		echo "erreur";exit;
	}

}

if(isset($_GET['Cible2ID'])){
	$cible2_id    	= $_GET['Cible2ID'];
	$cible2_type  	= $_GET['Cible2Type'];
	$cible2_nom  	= $_GET['Cible2Nom'];
	$cible2_isally	= $_GET['Cible2allie'];

	if(preg_match('#[a-z]#i',$cible2_id)){
		$_SESSION['action']['erreur'] = "Action interdite ! Compte banni pour une semaine.:erreur";
		//Ajouter un repport triche
		exit;
	}
}

$both=false;

if (isset($_GET['choix'])){
	$both=$_GET['choix'];
}

if($action_info['type_cible']=='choix'){
	if($cible_isally){
		($both!='false')?$action_info['type_cible']='both':$action_info['type_cible']='allie';
	}
	else {
		($both!='false')?$action_info['type_cible']='both':$action_info['type_cible']='ennemi';
	}
}

// if($perso_id==31){
	// $f=fopen("reportbug_magie",'a');
	// $string = print_r($action_info, true);
	// $string .= "\n";
	// $string .= "Choix : ".print_r($_GET['choix'], true);
	// $string .= "\n\n";
	// fwrite($f,$string);
	// fclose($f);
// }

$type_id   ='id';
$rchchrace ='';
$innerjoin ='';

if($cible_type=='persos'){
	$type='persos';
	$type_id='perso_id';
	$rchchrace = ", `persos`.`race_id` AS `race_id`, `persos`.`nom` AS `nom`, `races`.`camp_id` AS camp,
            `races`.`type` as type";
	$innerjoin = "INNER JOIN `persos` ON `persos`.`id`=`damier_persos`.`perso_id`
				  INNER JOIN `races` ON `races`.`race_id`=`persos`.`race_id`";
}
else {
	if($cible_type=='bouclier_1' || $cible_type=='bouclier_2' || $cible_type=='bouclier_3' || $cible_type=='bouclier_4'){
		$type='bouclier';
	}
	else if($cible_type=='porte_mauve'){
		$type='porte';
	}
	else $type=$cible_type;
}



if(isset($action_id)){
	$_SESSION['mort']['nb'] = 0 ;
	$_SESSION['destruction']['nb'] = 0 ;
	// S'il s'agit d'un entrainement ou d'une attaque au cac, on vérifie que la cible n'est pas partie
	$present2 = 1;
	if($action_info['type_action']=='attaque' || $action_info['type_action']=='entrainement'){
		if($type=='persos'){
			$sql        = "SELECT * FROM damier_$type WHERE $type_id='$cible_id'";
			$resultat    = mysql_query($sql)or die (mysql_error());
			$cible_pos    = mysql_fetch_array ($resultat);

			$present1 = ($cible_pos['carte_id']==$carte_pos && distance($cible_pos, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1);
		}
		else { //Si c'est un objet il n'a disparu que s'il est détruit
			$sql        = "SELECT * FROM damier_$type WHERE $type_id='$cible_id'";
			$resultat    = mysql_query($sql)or die (mysql_error());
			$cible_pos    = mysql_fetch_array ($resultat);
			//Les objets peuvent être déplacés
			if ($type=='objet_simple')
			$present1 = ($cible_pos['carte_id']==$carte_pos && distance($cible_pos, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=1);
			else $present1 = isset($cible_pos['carte_id']);
			if($present1){
				if(isset($_GET['Cible1_X'])){
					$cible_pos["pos_x"] = $_GET['Cible1_X'];
					$cible_pos["pos_y"] = $_GET['Cible1_Y'];
				}
			}
		}
	}
	else { //Sinon on verifie que le perso est dans le champs de vision
		if($type=='persos'){
			$sql        = "SELECT * FROM damier_$type WHERE $type_id='$cible_id'";
			$resultat    = mysql_query($sql)or die (mysql_error());
			$cible_pos    = mysql_fetch_array ($resultat);

			$present1 = ($cible_pos['carte_id']==$carte_pos && distance($cible_pos, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=$perception);

			//Si cible vaut 2 l'action agit sur deux personnages
			if($action_info['cible']==2){
				$sql         = "SELECT * FROM damier_$type WHERE $type_id='$cible2_id'";
				$resultat    = mysql_query($sql)or die (mysql_error());
				$cible2_pos  = mysql_fetch_array ($resultat);
				$present2    = ($cible2_pos['carte_id']==$carte_pos && distance($cible2_pos, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=$perception);
			}//Si cible vaut -1 l'action agit sur un personnage et une porte
			elseif ($action_info['cible']==-1){
				$sql         = "SELECT * FROM damier_porte WHERE id='$cible2_id'";
				$resultat    = mysql_query($sql)or die (mysql_error());
				$cible2_pos  = mysql_fetch_array ($resultat);
				$present2    = isset($cible2_pos['carte_id']);
			}
		}elseif($type=='none'){//Teleportation
			//recherche si la place est libre à coder. Récupérer le code du drop.
			$cible["pos_x"][1] = $_GET['Cible1_X'];
			$cible["pos_y"][1] = $_GET['Cible1_Y'];
			$new_pos['plan']   = $carte_pos;
			$new_pos['pos_x']  = $_GET['Cible1_X'];
			$new_pos['pos_y']  = $_GET['Cible1_Y'];
			$present1 = pos_is_free($new_pos);
		}
		else{
			//Si c'est un objet il n'a disparu que s'il est détruit
			//Les objets peuvent être déplacés
			$sql        = "SELECT * FROM damier_$type WHERE $type_id='$cible_id'";
			$resultat    = mysql_query($sql)or die (mysql_error());
			$cible_pos    = mysql_fetch_array ($resultat);
			if ($type=='objet_simple')
			$present1 = ($cible_pos['carte_id']==$carte_pos && distance($cible_pos, array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=$perception);
			else $present1 = isset($cible_pos['carte_id']);
		}

	}

	//Recupération du centre de la zone de l'action
	//Recuperation de la taille de la zone d'effet
	$zone=$action_info['zone'];
	if($zone==-2)
	{
		$zone =$caracs['perception'] ;
		if($action_info['lanceur']==2){
			$present1 = distance(array("pos_x"=>$cible["pos_x"][1],"pos_y"=>$cible["pos_y"][1]), array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=$zone;
		}
	}
	if($zone==-1)
	{
		$zone =round($caracs['perception']/2-0.5);
		if($action_info['lanceur']==2){
			$present1 = distance(array("pos_x"=>$cible["pos_x"][1],"pos_y"=>$cible["pos_y"][1]), array("pos_x"=>$pos_x_perso,"pos_y"=>$pos_y_perso), $carte_pos)<=$zone;
		}
	}
	if($zone==0 || $action_info['lanceur']==2)
	{
		$zone=0;
		$_SESSION['zone']=false;
	} else $_SESSION['zone']=true;


	if(!$present1 || !$present2){
		$_SESSION['action']['erreur'] = "La cible est inateignable !:erreur";
		exit;
	}

	//Recherche du centre de la zone
	if($action_info['cible']){
		$_SESSION['centre_effet']['pos_x'] = $cible_pos["pos_x"];
		$_SESSION['centre_effet']['pos_y'] = $cible_pos["pos_y"];
	}
	else {
		$_SESSION['centre_effet']['pos_x'] = $pos_x_perso;
		$_SESSION['centre_effet']['pos_y'] = $pos_y_perso;
	}

	// A partir d'ici on considère que la cible est au bon endroit par rapport au personnage effectuant l'action

	//Verification sur le fait que le perso dispose du nombre de PA suffisant
	if(($caracs['pa']+$caracs['pa_dec']/10)<$action_info['cout'] || ($caracs['pa']+$caracs['pa_dec']/10)<1)
	{
		$_SESSION['action']['erreur'] = "Vous n'&ecirc;tes pas suffisamment endurant pour faire autant d'actions";
		exit;
	}
	else {
		maj_carac($perso_id, "pa", $perso_carac_noalter['pa']-$action_info['cout']);
	}


	//Si l'action est un sort, le mage perd des mouvs
	if($action_info['type_action']=='sort'){

		$pa_max = $caracs_max['pa']+$caracs_max['pa_dec']/10;
		$mouv_max = $caracs_max['mouv'];

		// La perte correspond au cout de l'action / par le nombre de PA maximum du perso (en tenant compte des fraction de pa).
		// ce nombre est mutiplié par le max de mouv du perso, et arrondi au supérieur
		$perte = max(1,ceil($mouv_max * (($action_info['cout'] / $pa_max))));

		$caracs_alter      	= calcul_caracs_alter($perso_id);

		//maj_carac($perso_id, "mouv", max(0,$perso_carac_noalter['mouv'] - $perte));
		maj_carac($perso_id, "mouv", max(-1*$caracs_alter['alter_mouv'],$perso_carac_noalter['mouv'] - $perte));

	}

	//recherche de l'ensemble des cibles potentielles
	$nb_cible=0;
	$effets = explode(':',$action_info['id_effet']); //Liste des effets
	$effets_lanceur = explode(',',$effets[0]);
	$effets_cible	= explode(',',$effets[1]);
	if(isset($cible_id)){
		//faire un test sur le fait que la cible n'est pas une coordonnée

		// Ajouter un test sur le fait que la cible puisse être le supérieur
		if($action_info['cible']==-2){
			$sql = "SELECT superieur_id AS sup_id
					FROM persos
					WHERE id=$perso_id";
			$rep = mysql_query($sql)or die (mysql_error());
			$res = mysql_fetch_array($rep) ;
			$cible['id'][1] = $res['sup_id'];
			if(!$cible['id'][1]){
				maj_carac($perso_id, "pa", $perso_carac_noalter['pa']);
				$_SESSION['action']['erreur'] = "La cible est inateignable !";
				echo "erreur";exit;
			}
			$sup_id = $cible['id'][1];
			$sql = "SELECT nom AS sup_nom
							FROM persos
							WHERE id=$sup_id";
			$rep = mysql_query($sql)or die (mysql_error());
			$res = mysql_fetch_array($rep);

			$cible['type'][1] = 'persos';
			$cible['nom'][1]  = $res['sup_nom'];
			$cible['type_action'][1] = $action_info['type_action'];

			$nb_cible = 1;
		}elseif($action_info['cible'] > 2){
			//Les cibles ontdéjà été récupérées
			//On ne fait donc rien
		}// Ajouter une condition pour rancune.
		elseif($cible_id!=$perso_id || $cible_type!="persos" || $action_info['lanceur']==1 || $effets_cible[0]==0)
		{
			$cible['id'][1]=$cible_id;
			$cible['type'][1]=$cible_type;
			$cible['nom'][1]=$cible_nom;
			$cible['type_action'][1]=$action_info['type_action'];
			$nb_cible=1;
		}
	}

	//Si la zone est >0 on recupère les objets présents aussi, quel que soit le camp de l'objet
	if($zone>0 && $action_info['lanceur']!=2 && ($action_info['cible']==0 || $action_info['cible']==1)){
		//Recherche de l'ensemble des persos dans la zone
		$carte_pos = $_SESSION['persos']['carte'][$id];

		$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$carte = mysql_fetch_array ($resultat);

		$x_min_carte = $carte['x_min'];
		$x_max_carte = $carte['x_max'];

		$y_min_carte = $carte['y_min'];
		$y_max_carte = $carte['y_max'];

		if($action_info['cible']){
			if(($cible_pos["pos_y"]-$zone)>($y_min_carte) || !$carte['circ'][1]){
				$y_min = $cible_pos["pos_y"]-$zone;
			}
			else {
				$y_min = $y_max_carte + ($cible_pos["pos_y"]-$zone-$y_min_carte);
			}

			if(($cible_pos["pos_y"]+$zone)<($y_max_carte) || !$carte['circ'][1]){
				$y_max     = $cible_pos["pos_y"]+$zone;
			}
			else {
				$y_max     = $y_min_carte + ($cible_pos["pos_y"]+$zone-$y_max_carte);
			}

			if(($cible_pos["pos_x"]-$zone)>($x_min_carte) || !$carte['circ'][0]){
				$x_min    = $cible_pos["pos_x"]-$zone;
			}
			else {
				$x_min    = $x_max_carte + ($cible_pos["pos_x"]-$zone-$x_min_carte);
			}

			if(($cible_pos["pos_x"]+$zone)<($x_max_carte) || !$carte['circ'][0]){
				$x_max     = $cible_pos["pos_x"]+$zone;
			}
			else {
				$x_max     = $x_min_carte + ($cible_pos["pos_x"]+$zone-$x_max_carte);
			}

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
		}
		else {
			if(($pos_y_perso-$zone)>($y_min_carte) || !$carte['circ'][1]){
				$y_min = $pos_y_perso-$zone;
			}
			else {
				$y_min = $y_max_carte + ($pos_y_perso-$zone-$y_min_carte);
			}

			if(($pos_y_perso+$zone)<($y_max_carte) || !$carte['circ'][1]){
				$y_max     = $pos_y_perso+$zone;
			}
			else {
				$y_max     = $y_min_carte + ($pos_y_perso+$zone-$y_max_carte);
			}

			if(($pos_x_perso-$zone)>($x_min_carte) || !$carte['circ'][0]){
				$x_min    = $pos_x_perso-$zone;
			}
			else {
				$x_min    = $x_max_carte + ($pos_x_perso-$zone-$x_min_carte);
			}

			if(($pos_x_perso+$zone)<($x_max_carte) || !$carte['circ'][0]){
				$x_max     = $pos_x_perso+$zone;
			}
			else {
				$x_max     = $x_min_carte + ($pos_x_perso+$zone-$x_max_carte);
			}

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
		}
		$sql        ="SELECT `damier_$type`.`$type_id` AS id $rchchrace FROM `damier_$type` $innerjoin WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y GROUP BY `damier_$type`.`$type_id`";
		$resultat    = mysql_query($sql)or die (mysql_error());


		while($infos = mysql_fetch_array ($resultat)){

			if((isset($infos['race_id'])&&(($infos['id']!=$perso_id) || $action_info['lanceur']))&& $infos['id']!=$cible_id)
			{
				if(($infos['camp']==$camp_perso && ($action_info['type_cible']=='allie' || $action_info['type_cible']=='both'))||
				($infos['camp']!=$camp_perso && ($action_info['type_cible']=='ennemi' || $action_info['type_cible']=='both'))){
					$nb_cible++;
					$cible['id'][$nb_cible]=$infos['id'];
					$cible['type'][$nb_cible]=$type;
					$cible['race_type'][$nb_cible]=$infos['type'];
					$cible['nom'][$nb_cible]=$infos['nom'];
					$cible['type_action'][$nb_cible]=$action_info['type_action'];
					$cible['cible_action'][$nb_cible]=$action_info['type_cible'];
				}
			}
		}

		//Recherche de l'ensemble des objets simples de la zone
		if($action_info['cible']){
			$rchch_x ="(pos_x>='".($cible_pos["pos_x"]-$zone)."' AND pos_x<='".($cible_pos["pos_x"]+$zone)."')";
			$rchch_y ="(pos_y>='".($cible_pos["pos_y"]-$zone)."' AND pos_y<='".($cible_pos["pos_y"]+$zone)."')";
		}
		else {
			$rchch_x ="(pos_x>='".($pos_x_perso-$zone)."' AND pos_x<='".($pos_x_perso+$zone)."')";
			$rchch_y ="(pos_y>='".($pos_y_perso-$zone)."' AND pos_y<='".($pos_y_perso+$zone)."')";
		}
		$sql        ="SELECT `damier_objet_simple`.`id` AS id, case_objet_simple.`nom` AS nom FROM damier_objet_simple
										INNER JOIN `case_objet_simple` ON `case_objet_simple`.`id`=`damier_objet_simple`.`case_objet_simple_id`
											WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y";
		$resultat    = mysql_query($sql)or die (mysql_error());

		while($infos = mysql_fetch_array ($resultat)){
			$nb_cible++;
			$cible['id'][$nb_cible]=$infos['id'];
			$cible['type'][$nb_cible]='objet_simple';
			$cible['nom'][$nb_cible]=$infos['nom'];
			$cible['type_action'][$nb_cible]=$action_info['type_action'];
		}

		//Recherche de l'ensemble des objets complexes de la zone
		if($action_info['cible']){
			$rchch_x ="((pos_x>='".($cible_pos["pos_x"]-$zone)."' AND pos_x<='".($cible_pos["pos_x"]+$zone)."') OR (pos_x_max>='".($cible_pos["pos_x"]-$zone)."' AND pos_x_max<='".($cible_pos["pos_x"]+$zone)."'))";
			$rchch_y ="((pos_y>='".($cible_pos["pos_y"]-$zone)."' AND pos_y<='".($cible_pos["pos_y"]+$zone)."') OR (pos_y_max>='".($cible_pos["pos_y"]-$zone)."' AND pos_y_max<='".($cible_pos["pos_y"]+$zone)."'))";
		}
		else {
			$rchch_x ="((pos_x>='".($pos_x_perso-$zone)."' AND pos_x<='".($pos_x_perso+$zone)."') OR (pos_x_max>='".($pos_x_perso-$zone)."' AND pos_x_max<='".($pos_x_perso+$zone)."'))";
			$rchch_y ="((pos_y>='".($pos_y_perso-$zone)."' AND pos_y<='".($pos_y_perso+$zone)."') OR (pos_y_max>='".($pos_y_perso-$zone)."' AND pos_y_max<='".($pos_y_perso+$zone)."'))";
		}
		$sql        ="SELECT `damier_objet_complexe`.`id` AS id, case_objet_complexe.`nom` AS nom FROM damier_objet_complexe
										INNER JOIN `case_objet_complexe` ON `case_objet_complexe`.`id`=`damier_objet_complexe`.`case_objet_complexe_id`
											WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y";
		$resultat    = mysql_query($sql)or die (mysql_error());

		while($infos = mysql_fetch_array ($resultat)){
			$nb_cible++;
			$cible['id'][$nb_cible]=$infos['id'];
			$cible['type'][$nb_cible]='objet_complexe';
			$cible['nom'][$nb_cible]=$infos['nom'];
			$cible['type_action'][$nb_cible]=$action_info['type_action'];
		}

		//Recherche de l'ensemble des portes de la zone
		if($action_info['cible']){
			$rchch_x ="(pos_x>='".($cible_pos["pos_x"]-$zone-4)."' AND pos_x<='".($cible_pos["pos_x"]+$zone)."')";
			$rchch_y ="(pos_y>='".($cible_pos["pos_y"]-$zone)."' AND pos_y<='".($cible_pos["pos_y"]+$zone+4)."')";
		}
		else {
			$rchch_x ="(pos_x>='".($pos_x_perso-$zone-4)."' AND pos_x<='".($pos_x_perso+$zone)."')";
			$rchch_y ="(pos_y>='".($pos_y_perso-$zone)."' AND pos_y<='".($pos_y_perso+$zone+4)."')";
		}
		$sql        ="SELECT `damier_porte`.`id` AS id, `nom_image` AS type, `damier_porte`.`nom` AS nom FROM damier_porte WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y";
		$resultat    = mysql_query($sql)or die (mysql_error());
		while($infos = mysql_fetch_array ($resultat)){
			$nb_cible++;
			$cible['id'][$nb_cible]=$infos['id'];
			if($infos['type']=='PorteMauve_parias'){
				$cible['type'][$nb_cible]='porte_mauve';
			}else $cible['type'][$nb_cible]='porte';

			$cible['nom'][$nb_cible]=$infos['nom'];
			$cible['type_action'][$nb_cible]=$action_info['type_action'];
		}

		//Recherche de l'ensemble des boucliers de la zone
		if($action_info['cible']){
			$rchch_x ="(pos_x>='".($cible_pos["pos_x"]-$zone-4)."' AND pos_x<='".($cible_pos["pos_x"]+$zone)."')";
			$rchch_y ="(pos_y>='".($cible_pos["pos_y"]-$zone)."' AND pos_y<='".($cible_pos["pos_y"]+$zone+4)."')";
		}
		else {
			$rchch_x ="(pos_x>='".($pos_x_perso-$zone-4)."' AND pos_x<='".($pos_x_perso+$zone)."')";
			$rchch_y ="(pos_y>='".($pos_y_perso-$zone)."' AND pos_y<='".($pos_y_perso+$zone+4)."')";
		}
		$sql        ="SELECT `damier_bouclier`.`id` AS id, `damier_bouclier`.`nom_image` AS type , `damier_bouclier`.`nom` AS nom FROM damier_bouclier WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y";
		$resultat    = mysql_query($sql)or die (mysql_error());

		while($infos = mysql_fetch_array ($resultat)){
			$nb_cible++;
			$cible['id'][$nb_cible]=$infos['id'];
			$cible['nom'][$nb_cible]=$infos['nom'];
			$cible['type'][$nb_cible]=$infos['type'];
			$cible['type_action'][$nb_cible]=$action_info['type_action'];
		}

	}



	$reussite=1; //Vaut 0 en cas d'EC, 1 en cas de coup normal, 2 en cas de CC
	if($action_info['type_action']=='sort' && !isset($action_info['id_perso'])) {
		//Calcul de la réussite du sort si non fait. Les sorts du grimoire réussisse forcément
		// echo "Warning reussite sort<br/>";

		$chance = '';
		$reussite = reussite_sort($grade_perso, $action_info['niv'], $percept_up);


		// $somme = 0;
		// for($incii=0; $incii<=15000;$incii++){
		// $reussitetest=reussite_sort($race_perso,$grade_perso, $caracs['niv'], $action_info['niv']);
		// $somme+=$reussitetest;

		// }
		// echo (100*$somme/15000);
	}

	//Mise en session de la réussite
	$_SESSION['reussite'] = $reussite;

	if($reussite) {

		// La valeur est multiplié par le coefficiant. Avec un coefficiant de 1, il n'y a pas de changement
		$coef = 1;

		// Si c'est un sort, on calcule le coefficiant
		if($action_info['type_action']=='sort') {
			$softcap = 1.5; // Le softcap détermine la valeur maximum du coefficiant atteignable par la magie
			$hardcap = 3.5; // le hardcap détermine la valeur maximum du coefficiant total (coef de base + coef de magie + coef de perception)

			// Coefficiant via le niveau du mage
			// le coefficiant est (Niveau du mage - niveau du sort)
			$coef_magie = ($caracs['niv'] - $action_info['niv'])/5;

			// Coefficiant via la perception
			$coef_percept = min($softcap,($percept_up / 20));

			$coef += min($hardcap,($coef_magie + $coef_percept));
		}
		$tableau_effets = recup_tableau_effets($action_info['id_effet'], $coef);

		//echo "Warning esquive sort<br/>";
		$esquive=1;
		//Application des effets à toutes les cibles
		$_SESSION['mort']['valid'] = false;

		$_SESSION['esquive']['nb'] = $nb_cible;
		$_SESSION['effet']['cible']['nb']=0;
		$_SESSION['event_effect']['nb'] = 0;
		$_SESSION['effet']['lanceur']['nb']=0;

		for($inci=1;$inci<=$nb_cible;$inci++){


			$_SESSION['esquive']['nom'][$inci] = $cible['nom'][$inci];
			$_SESSION['esquive']['mat'][$inci] = $cible['id'][$inci];
			$_SESSION['esquive']['type'][$inci] = $cible['type'][$inci];



			//si l'effet s'applique sur un persos on recupere ses caracs
			//si ce n'est pas un perso c'est un objet l'absence d'esquive est donc systematique
			if($cible['type'][$inci]=='persos'){
				$cible_id = $cible['id'][$inci];
				$_SESSION['gain_xp']['def'][$cible_id] = 0;

				// Recupération d'infos basiques sur la cible
				$sql        ="SELECT * FROM persos WHERE id='".$cible['id'][$inci]."'";
				$resultat    = mysql_query($sql)or die (mysql_error());
				$cible_info    = mysql_fetch_array ($resultat);


				$sql="SELECT maj_esq_mag FROM caracs WHERE perso_id=".$cible_id;
				$reponse=mysql_query($sql) or die(mysql_error());
				$maj_des=mysql_fetch_array($reponse);
				$maj_esq_mag=$maj_des['maj_esq_mag'];

				//Recuperation des caracs alterées
				$cible_caracs     = calcul_caracs($cible['id'][$inci]);
				$cible_res_mag = recup_carac_alter_mag($cible_id, "alter_res_mag");
				$cible_res_mag = $cible_res_mag["alter_res_mag"];
				//Recupération de caracs non altérées
				$cible_carac_noalter=recup_carac($cible['id'][$inci], array('pv', 'malus_def'));

				//ajouter un test sur le fait que a cible2 puisse ne pas être un perso
				if($cible2_id!='' && $cible2_type=='persos'){
					// Recupération d'infos basiques sur la cible
					$sql        ="SELECT * FROM persos WHERE id='".$cible2_id."'";
					$resultat    = mysql_query($sql)or die (mysql_error());
					$cible2_info    = mysql_fetch_array ($resultat);

					$sql="SELECT maj_esq_mag FROM caracs WHERE perso_id=".$cible2_id;
					$reponse=mysql_query($sql) or die(mysql_error());
					$maj_des=mysql_fetch_array($reponse);
					$maj_esq_mag2=$maj_des['maj_esq_mag'];
				}

				//Si c'est une attaque on calcule l'esquive pour chaque cibles
				//La reussite est systématique
				if($action_info['type_action']=='attaque'){

					// Calcul des dés de défense
					$cible_des_def    = carac_max ($cible_info['race_id'], $cible_info['grade_id'], 'des', $cible_caracs['niv_des'], $cible_id) - $cible_caracs['des_attaque'];

					//Lancé des jets d'attaque et de défense
					$des = \conf\Helpers::Dice($caracs['des_attaque'], $cible_des_def);
					$perso_jet        = $des[0] + $caracs['att'];
					$cible_jet        = $des[1] + $cible_caracs['def'] - $cible_caracs['malus_def'];

					$_SESSION['score']['att'] = $perso_jet;
					$_SESSION['score']['def'] = $cible_jet;

					$esquive = ($perso_jet < $cible_jet);
					$_SESSION['demi_esquive']=($perso_jet == $cible_jet);
					//Mise en session des jets et de la réussite
					$_SESSION['temp']['info_action'] = "Jet attaque : ".$perso_jet." (".$caracs['des_attaque']."des)<br/>Jet defense : ".$cible_jet." (".$cible_des_def."des)";
				}
				//Si c'est un sort on calcule la réussite du sort une fois pour toute
				//Puis on calcule l'esquive pour chaque cible
				elseif($action_info['type_action']=='sort'){

					// Calcul de l'esquive, vaut 1 si reussite =0
					//echo "Warning esquive sort<br/>";

					$esquive_auto = ($maj_esq_mag==1 || $maj_esq_mag==2);
					$esquive    = (esquive_sort($caracs['px'], $grade_perso, $cible_caracs['px'], $cible_info['grade_id'], $esquive_auto, $cible_caracs['esq_mag']) || !$reussite);

					$_SESSION['esquive']['somme_rang']+=calcul_rang($cible_caracs['px']);
					$_SESSION['esquive']['table_rang'][] = calcul_rang($cible_caracs['px']);
					// $somme = 0;
					// for($incii=0; $incii<=15000;$incii++){
					// $reussitetest=esquive_sort($caracs['px'], $grade_perso, $cible_caracs['px'], $cible_info['grade_id']);
					// $somme+=$reussitetest;

					// }
					// echo (100*$somme/15000);
					if($cible2_id!='' && $cible2_type=='persos'){
						$esquive_auto2 = ($maj_esq_mag2==1 || $maj_esq_mag2==2);
						$esquive    = $esquive || (esquive_sort($caracs['px'], $grade_perso, $cible_caracs['px'], $cible_info['grade_id'], $esquive_auto2) || !$reussite);
						$_SESSION['esquive']['val'][2] = $esquive;
					}

				}elseif($action_info['type_action']=='aura'){

					$esquive    = ( $grade_perso <= $cible_info['grade_id']);

				}else {
					$esquive=0;
				}
				$rtm = ($maj_esq_mag==1 || $maj_esq_mag==2) ? 0 : $cible_res_mag;
			}
			//sinon l'absence d'esquive est systématique
			else {
				$esquive=0;

				// La RTM est a 0
				$rtm = 0;
			}


			//Mise en session de l'esquive
			$_SESSION['esquive']['val'][$inci] = $esquive;

			$_SESSION['mort']['valid']=false;
			if(!$esquive){
				//recuperation des id des effets
				if($_SESSION['effet']['cible']['nb']==0 && $action_info['type_action']=='sort'){
					$incj=0;
					while(isset($effets_cible[$incj]) && $effets_cible[$incj]!=0){
						$effet_id=$effets_cible[$incj];
						//recup des infos sur l'effet
						$effet_info    = $tableau_effets[$effet_id];
						$_SESSION['effet']['cible']['nom'][++$incj]=$effet_info['type_effet'];
						$_SESSION['effet']['cible']['val'][$incj]=$effet_info['effet'];
						$_SESSION['effet']['cible']['nb']=$incj;
					}
					$incj=0;
					while(isset($effets_lanceur[$incj]) && $effets_lanceur[$incj]!=0){
						$effet_id=$effets_lanceur[$incj];
						//recup des infos sur l'effet
						$effet_info    = $tableau_effets[$effet_id];
						$_SESSION['effet']['lanceur']['nom'][++$incj]=$effet_info['type_effet'];
						$_SESSION['effet']['lanceur']['val'][$incj]=$effet_info['effet'];
						$_SESSION['effet']['lanceur']['nb']=$incj;
					}
				}

				if(($cible['type'][$inci]=='persos' || $cible['type'][$inci]=='none') && $action_info['type_action']!='suicide'){
					if($cible['id'][$inci]=='' || $cible['id'][$inci]==NULL){
						$cible['id'][$inci]=$perso_id;
					}
					calcul_xp($perso_id, $cible['id'][$inci], $action_info['type_action'], 1, 0, $action_info['cout']);
				}
				if($inci==1){
					// Application des effets sur le lanceur
					$incj=0;
					while(isset($effets_lanceur[$incj]) && $effets_lanceur[$incj]!=0){
						if($incj==($_SESSION['effet']['lanceur']['nb']-1) && $_SESSION['effet']['cible']['nb']==0){
							if($inci==$nb_cible){
								$_SESSION['mort']['valid']=true;
							}
						}
						$_SESSION['event_effect']['nb']+=$incj+1;
						applique_effet($tableau_effets, $effets_lanceur[$incj], array($perso_id, 'persos', $action_info['type_action'], "cible_x"=>$cible['pos_x'][1], "cible_y"=>$cible['pos_y'][1]), null, $rtm);
						$incj++;
					}

					// Application des effets sur la deuxieme cible du sort
					if($cible2_id!=''){
						if($cible2_type!='porte'){
							$_SESSION['esquive']['nb'] = 2;
							$_SESSION['esquive']['nom'][2] = $cible2_nom;
							$_SESSION['esquive']['mat'][2] = $cible2_id;
							$_SESSION['esquive']['type'][2] = $cible2_type;
						}
						$incj=0;
						while(isset($effets_cible[$incj]) && $effets_cible[$incj]!=0){
							$_SESSION['event_effect']['nb']+=$incj+1;

							applique_effet($tableau_effets, $effets_cible[$incj], array($cible['id'][$inci], 'persos', $action_info['type_action']), array($cible2_id, $cible2_type, $action_info['type_action']), $rtm);
							$incj++;
						}
					}
				}
				//Application de chacuns des effets sur les autres cibles
				$incj=0;

				while(isset($effets_cible[$incj]) && $cible2_type!='porte' && $effets_cible[$incj]!=0 && $action_info['cible']!=2){
					$_SESSION['event_effect']['nb']+=$incj+1;
					if($incj==($_SESSION['effet']['cible']['nb']-1)){
						if($inci==$nb_cible){
							$_SESSION['mort']['valid']=true;
						}
					}
                                        //TODO : moduler
                                        $tbl_effets = $tableau_effets;
                                        if($cible['type'][$inci] == 'persos' && $cible['race_type'][$inci] == 4){
                                            foreach($tbl_effets as $key => $value){
                                                if($value['type_effet'] == 'pv'){
                                                    $value['effet'] /= 2;
                                                    //Modulation pv T4
                                                    $tbl_effets[$key] = $value;                                                      $_SESSION['debug']['message'] .= ob_get_clean();
                                                }
                                            }
                                        }

					applique_effet($tbl_effets, $effets_cible[$incj], array($cible['id'][$inci], $cible['type'][$inci], $action_info['type_action']), null, $rtm);
					$incj++;
				}

			}
			else{
				if($inci==$nb_cible){
					$_SESSION['mort']['valid']=true;
				}
				if($action_info['type_action']=='attaque'){
					$malus = max(1,(($caracs['force']/2) - ($caracs['force']/2)%10)/10);
					if(($caracs['force']/2)%10!=0){
						$reste = 10*(($caracs['force']/2)%10);
						$test = lance_ndp(1,100);

						if($test<=$reste){
							$malus = max(1,(($caracs['force']/2) - ($caracs['force']/2)%10)/10 + 1);
							} else $malus = max(1,(($caracs['force']/2) - ($caracs['force']/2)%10)/10);
					}
					$_SESSION['event_effect']['malus']=$malus;
					maj_carac($cible['id'][$inci], "malus_def", $cible_carac_noalter['malus_def']+$malus);


					/*Maj du 31/10/2011 Elestel :
					 * Ajout des guards
					*/

					// On calcul un coef d'écart dans [0,1[
						// 0 => écart max
						// 1 => écart min
					$coef = 1;
					if($_SESSION['score']['def'] != 0)
						$coef = 1 - ($_SESSION['score']['def'] - $_SESSION['score']['att'])/$_SESSION['score']['def'];
					if($coef < 0) // au cas où il y aurait des malus sur l'attaque
						$coef = 0;

					$force_max_guard = $caracs['force']*0.25;

					//la formule : 0.25*force * exp((ln(1) - ln(0.25*force) * (1-coef)) : permet d'avoir une fonction expo de [0,1[ dans [1,0.25*force]

					$guards = exp((log(1) - log($force_max_guard))*(1 - $coef));
					$guards = round($force_max_guard*$guards);
					$pv_guard = $cible_carac_noalter['pv']-$guards;

					$_SESSION['GD']['deg'] = $guards;
					if($pv_guard <= 0){
						$pv_guard = 1;
						$_SESSION['GD']['deg'] = 0;
					}

					maj_carac($cible['id'][$inci], 'pv', $pv_guard);
				}
				$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']." Esquivé ! ";

				calcul_xp($perso_id, $cible['id'][$inci], $action_info['type_action'], 1, 1, $action_info['cout']);
			}

			if($inci==$nb_cible && $zone!=0 && $action_info['type_action']=='sort'){
				calcul_xp($perso_id, NULL, 'reussite_sort', 1, 0, $action_info['cout']);
			}
			if($action_info['type_action']=='sort'){
				if(!$esquive){
					$resistance = min(80,$cible_res_mag + ($action_info['cout'] * 5));
					if($cible['type'][$inci]=='persos')
						maj_carac_alter_mag($cible_id, "alter_res_mag", $resistance);
					if($cible2_id!='' && $cible2_type=='persos'){
						maj_carac_alter_mag($cible2_id, "alter_res_mag", $resistance);
					}
				}else{
					$resistance = max(0,$cible_res_mag - ceil(($action_info['cout'] * 5)/2));
					if($cible['type'][$inci]=='persos')
						maj_carac_alter_mag($cible_id, "alter_res_mag", $resistance);
					if($cible2_id!='' && $cible2_type=='persos'){
						maj_carac_alter_mag($cible2_id, "alter_res_mag", $resistance);
					}
				}
			}
		}
	} else {
		calcul_xp($perso_id, null, $action_info['type_action'], 0, 0, $action_info['cout']);
	}
	if($reussite  && $nb_cible==0){
		if($zone!=0 && $action_info['type_action']=='sort'){
			calcul_xp($perso_id, NULL, 'reussite_sort', 1, 0, $action_info['cout']);
		}
	}
	if(isset($_SESSION['action']['type'])){
		$idAction = $_SESSION['action']['id'];
		$type_action = $_SESSION['action']['type'];
	}

	$current_perso_id = $_SESSION['persos']['current_id'];
	$l_perso_vict='';
	$l_os_vict='';
	$l_oc_vict='';
	$l_p_vict='';
	$l_b_vict='';
	$l_perso_mort='';
	$l_os_det='';
	$l_oc_det='';
	$l_p_det='';
	$l_b_det='';
	$l_esquive='';

	for($inci=1;$inci<=$_SESSION['mort']['nb'];$inci++){
		$l_perso_mort.=(($l_perso_mort=='')?$_SESSION['mort']['id'][$inci]:','.$_SESSION['mort']['id'][$inci]);
	}

	for($inci=1;$inci<=$_SESSION['destruction']['nb'];$inci++){
		if($_SESSION['destruction']['type'][$inci]=='objet_simple'){
			if($l_os_det==''){
				$l_os_det=$_SESSION['destruction']['id'][$inci];
			}else $l_os_det.= ','.$_SESSION['destruction']['id'][$inci];
		}
		if($_SESSION['destruction']['type'][$inci]=='objet_complexe'){
			if($l_oc_det==''){
				$l_oc_det=$_SESSION['destruction']['id'][$inci];
			}else $l_oc_det.= ','.$_SESSION['destruction']['id'][$inci];
		}
		if($_SESSION['destruction']['type'][$inci]=='porte' || $_SESSION['destruction']['type'][$inci]=='porte_auve'){
			if($l_p_det==''){
				$l_p_det=$_SESSION['destruction']['id'][$inci];
			}else $l_p_det.= ','.$_SESSION['destruction']['id'][$inci];
		}
		if($_SESSION['destruction']['type'][$inci]=='bouclier'){
			if($l_b_det==''){
				$l_b_det=$_SESSION['destruction']['id'][$inci];
			}else $l_b_det.= ','.$_SESSION['destruction']['id'][$inci];
		}
	}

	/* =========================================================================
	 * GESTION DES EVENEMENTS
	 * ========================================================================= */

	$evman = new persos\event\eventManager();

	//$_SESSION['debug_event0'] = $_SESSION;
if(!isset($_SESSION['gain_xp']['att']))
	$_SESSION['gain_xp']['att']=0;
	switch($type_action){
		case 'attaque':
			$id_cible=(int)$_SESSION['esquive']['mat'][1];
			$ev_att = $evman->createEvent('attaque');
			// infos intervenants
			$ev_att->setSource($current_perso_id, 'perso');
			$ev_att->setAffected($id_cible,$_SESSION['esquive']['type'][1]);
			// infos attaque
			$ev_att->infos->addPrivateInfo('att',$_SESSION['score']['att']);
			$ev_att->infos->addPrivateInfo('def',$_SESSION['score']['def']);
			// attaque sur un perso
			$ev_att->infos->addPrivateInfo('xpD',$_SESSION['gain_xp']['def'][$id_cible]);
			$ev_att->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
			//state == 1 : esquive
			if(!$_SESSION['esquive']['val'][1] && !isset($_SESSION['GD'])){
				$ev_att->setState(1);
				$ev_att->infos->addPrivateInfo('deg',$_SESSION['score']['degats']);
			}else{
				$ev_att->infos->addPrivateInfo('deg',$_SESSION['GD']['deg']);
			}
			// state == 5 : mort
			if($_SESSION['mort']['nb']>0){
				$ev_att->setState(5);
				$evman->addToCV($current_perso_id, $_SESSION['esquive']['mat'][1], $_SESSION['esquive']['nom'][1], $type_action, $carte_pos);
			}elseif($_SESSION['destruction']['nb']>0){
				$ev_att->setState(4);
			}
			break;
		case 'sort':
			$ev_sort = $evman->createEvent("sort");
			$ev_sort->setSource($current_perso_id, eventFormatter::convertType('perso'));
			$ev_sort->infos->addPublicInfo('s',$idAction);
			$ev_sort->infos->addPublicInfo('c',$camp_perso);
			$ev_sort->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
			if(isset($_SESSION['reussite']) && $_SESSION['reussite']){
				// state == 1 : reussi // state == 2 : esquive

				$ev_sort->setState(1);
				for($i=1;$i<=$_SESSION['esquive']['nb'];$i++){
					$cible = $_SESSION['esquive']['mat'][$i];
					if($_SESSION['zone']){
						$ev_zone = $evman->createEvent("sort");
					}else{
						$ev_zone = &$ev_sort;
					}
					$ev_zone->setSource($current_perso_id, eventFormatter::convertType('perso'));
					$ev_zone->setAffected($cible, eventFormatter::convertType($_SESSION['esquive']['type'][$i]));

					if(isset($_SESSION['gain_xp']['def'][$cible])){
						$ev_zone->infos->addPrivateInfo('xpD',$_SESSION['gain_xp']['def'][$cible]);
					}

					if(!$_SESSION['esquive']['val'][$i]){
						if(isset($_SESSION['mort']['nb']) && $_SESSION['mort']['nb'] > 0 && isset($_SESSION['mort']['id']) && $idm=array_search($cible,$_SESSION['mort']['id'])){
							$evman->addToCV($current_perso_id, $cible, $_SESSION['esquive']['nom'][$i], $type_action, $carte_pos);
							$ev_zone->setState(5);
						}elseif(isset($_SESSION['destruction']['id']) && $idd=array_search($cible,$_SESSION['destruction']['id'])){
							$ev_zone->setState(4);
						}else{
							$ev_zone->setState(1);
						}
					}else{
						$ev_zone->setState(2);
					}
					$ev_zone->infos->addPublicInfo('s',$idAction);
					$ev_zone->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
					if(isset($_SESSION['gain_xp']['def'][$i])){
						$ev_sort->infos->addPrivateInfo('xpD',$_SESSION['gain_xp']['def'][$i]);
					}
					if($_SESSION['zone']){
						$ev_zone->setMaster($ev_sort);
					}
				}
			}
			break;
		case 'sprint':
			$ev_sprint = $evman->createEvent('sprint');
			$ev_sprint->setSource($current_perso_id,eventFormatter::convertType('perso'));
			$ev_sprint->infos->addPrivateInfo('xp',$_SESSION['gain_xp']['att']);
			break;
		case 'suicide':
			$ev_suic = $evman->createEvent('suicide');
			$ev_suic->setSource($current_perso_id,eventFormatter::convertType('perso'));
			$ev_suic->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
			if(isset($_SESSION['reussite']) && $_SESSION['reussite']){
				// state == 1 : reussi
				$ev_suic->setState(1);
				$ev_suic->infos->addPrivateInfo('nb',$_SESSION['persos']['nb_suicide'][$_SESSION['persos']['id'][0]]);
			}
			break;
		case 'entrainement':
			$cible_id = $_SESSION['esquive']['mat'][1];
			$ev_entr = $evman->createEvent('entraine');
			$ev_entr->setSource($current_perso_id, eventFormatter::convertType('perso'));
			$ev_entr->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
			if(isset($_SESSION['gain_xp']['def'][$cible_id]) && $cible_id != $current_perso_id){
				$ev_entr->setAffected($cible_id,eventFormatter::convertType('perso'));
				$ev_entr->infos->addPrivateInfo('xpD',$_SESSION['gain_xp']['def'][$cible_id]);
			}
			break;
		case 'reparation':
			$cible_id = $_SESSION['reparation']['cible'];
			$ev_entr = $evman->createEvent('reparation');
			$ev_entr->setSource($current_perso_id, eventFormatter::convertType('perso'));
			$ev_entr->infos->addPrivateInfo('xpA',$_SESSION['gain_xp']['att']);
			$ev_entr->setAffected($_SESSION['reparation']['cible'] ,eventFormatter::convertType($_SESSION['reparation']['type']));
			$ev_entr->infos->addPrivateInfo('xpD',0);

		break;
	}

	// GESTION DES EVENTS AVEC PLUSIEURS CIBLES
	for($inci=1;$inci<=$_SESSION['esquive']['nb'];$inci++){
		if($_SESSION['esquive']['val'][$inci]){
			if($l_esquive==''){
				$l_esquive = $_SESSION['esquive']['mat'][$inci];
			} else {
				$l_esquive .= ','.$_SESSION['esquive']['mat'][$inci];
			}
		} else {
			switch($_SESSION['esquive']['type'][$inci]){
				case 'perso':
				case 'persos':
					if($l_perso_vict==''){
						$l_perso_vict = $_SESSION['esquive']['mat'][$inci];
					} else {
						$l_perso_vict .= ','.$_SESSION['esquive']['mat'][$inci];
					}
					break;
				case 'objet_simple':
					if($l_os_vict==''){
						$l_os_vict = $_SESSION['esquive']['mat'][$inci];
					} else {
						$l_os_vict .= ','.$_SESSION['esquive']['mat'][$inci];
					}
					break;
				case 'objet_complexe':
					if($l_oc_vict==''){
						$l_oc_vict = $_SESSION['esquive']['mat'][$inci];
					} else {
						$l_oc_vict .= ','.$_SESSION['esquive']['mat'][$inci];
					}
					break;
				case 'porte':
				case 'porte_mauve':
					if($l_p_vict==''){
						$l_p_vict = $_SESSION['esquive']['mat'][$inci];
					} else {
						$l_p_vict .= ','.$_SESSION['esquive']['mat'][$inci];
					}
					break;
				case 'bouclier':
					if($l_bvict==''){
						$l_b_vict = $_SESSION['esquive']['mat'][$inci];
					} else {
						$l_b_vict .= ','.$_SESSION['esquive']['mat'][$inci];
					}
					break;
				default:
			}
		}
	}

	// Uniquement lorsque les actions se feront via JS
	if($_SESSION['mort']['nb'] > 0 || $_SESSION['destruction']['nb'] > 0){
		exit;
	}


}
mysql_close($ewo_bdd);
?>

