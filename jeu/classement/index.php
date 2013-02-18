<?php

namespace jeu\classement;

/**
 * Classement new format
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
//-- Header --

require_once __DIR__ . '/../../conf/master.php';

$css_files = 'classement,listeperso';

include(SERVER_ROOT . "/template/header_new.php");

include(SERVER_ROOT . "/persos/fonctions.php");

include ('ClassementListeDAO.php');
include ('ClassementVUE.php');

ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];

if (isset($_POST['perso_id'])){
	$param['id'] = mysql_real_escape_string($_POST['perso_id']);
}elseif(isset($_GET['perso_id'])){
	$param['id'] = mysql_real_escape_string($_GET['perso_id']);
}else{
	$param['id'] = 0;
	//echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
	//exit;
}

$param['id'] = 0;

// On récupère la page courrante
if(!empty($_GET['page'])) {
	$param['page'] = $_GET['page'];
} else {
	$param['page']=1;
}

// On récupère et filtre le nombre d'ꭩments
if(!empty($_POST['nb_el'])) {
	$param['nb_el'] = $_POST['nb_el'];
} elseif(!empty($_GET['nb_el'])) {
	$param['nb_el'] = $_GET['nb_el'];
} else {
	$param['nb_el']=50;
}

$param['aujourdhui'] = false;

// On récupère la date
if(!empty($_POST['date'])) {
	$param['timestamp'] = strtotime($_POST['date']);
	if($_POST['date'] == date("d-m-Y")) {
		$param['aujourdhui'] = true;
	}
} elseif(!empty($_GET['date'])) {
	$param['timestamp'] = strtotime($_GET['date']);
	if($_GET['date'] >= date("d-m-Y")) {
		$param['aujourdhui'] = true;
	}	
} else {
	$param['timestamp']=time();
	$param['aujourdhui'] = true;	
}

// On récupère les races souhait곮 Obsol鵥
if (!empty($_POST['race'])) {
	$param['race'] = mysql_real_escape_string($_POST['race']);
	}
	elseif (!empty($_GET['race'])){
		$param['race'] = mysql_real_escape_string($_GET['race']);
		}
		else $param['race']=0;
		
if($param['race'] > 0){
	$camp = recup_camp($param['race']);
	} else $camp = $param['race'];
	
switch($camp){
	case 1 :
		$param['type']="humain";
		$choix_race = ClassementDAO::HUMAIN;
		break;
	case 2 :
		$param['type']="paria";
		$choix_race = ClassementDAO::PARIA;
		break;
	case 3 :
		$param['type']="ang&eacute;lique";
		$choix_race = ClassementDAO::ANGE;
		break;
	case 4 :
		$param['type']="d&eacute;moniaque";
		$choix_race = ClassementDAO::DEMON;
		break;
	case -1 :
		$param['type']="ang&eacute;monique";
		$choix_race = ClassementDAO::AILE;
		break;
	default :
		$param['type']="toutes races";
		$choix_race = ClassementDAO::TOUS;
	};

// On récupère la demande de tri par grade	
if (isset($_POST['grade_ord'])){
	$param['grade_ord'] = $_POST['grade_ord'];
}
elseif (isset($_GET['grade_ord'])) {
	$param['grade_ord'] = $_GET['grade_ord'];
} else {
	$param['grade_ord']=0;
}

switch($param['grade_ord']) {
	case 1:
		$triGrade = ClassementDAO::GRADESANSGALON;
		break;	
	case 2:
		$triGrade = ClassementDAO::GRADEGALON;
		$param['afficheGalon'] = true;
		break;	
	case 0:
	default:
		$triGrade = ClassementDAO::SANSGRADE;
		break;		
}

// On récupère le type du classement
if (isset($_POST['classement_type'])){
	$param['classement_type'] = $_POST['classement_type'];
} elseif (isset($_GET['classement_type'])){
	$param['classement_type'] = $_GET['classement_type'];
} else {
	$param['classement_type'] = 'xp';
}

switch($param['classement_type']) {
	case 'meurtre':
		$typeClassement = ClassementDAO::MEURTRE;
		$param['afficheXp'] = true;
		$param['afficheGrade'] = true;
		$param['afficheMeurtre'] = true;
		$param['afficheRecherche'] = true;
		break;	
	case 'mort':
		$typeClassement = ClassementDAO::MORT;
		$param['afficheXp'] = true;
		$param['afficheGrade'] = true;
		$param['afficheMort'] = true;
		$param['afficheRecherche'] = true;
		break;	
	case 'cv':
		$typeClassement = ClassementDAO::TAILLECV;
		$param['afficheGrade'] = true;
		$param['afficheMeurtre'] = true;
		$param['afficheMort'] = true;
		$param['afficheCv'] = true;
		break;		
	case 'survie':
		$typeClassement = ClassementDAO::SURVIE;
		$param['afficheGrade'] = true;
		$param['afficheXp'] = true;
		$param['afficheDateMort'] = true;
		break;	
	case 'famille':
		$typeClassement = ClassementDAO::FAMILLE;
		$param['afficheXpFamille'] = true;
		break;				
	case 'xp':
	default:
		$typeClassement = ClassementDAO::XP;
		$param['afficheXp'] = true;
		$param['afficheGrade'] = true;
		$param['afficheRecherche'] = true;
		break;		
}

	
if(isset($_POST['highlight'])) {
	$highlight = $_POST['highlight'];
} elseif(isset($_GET['highlight'])) {
	$highlight = $_GET['highlight'];
}
	

			$param['page_max']=1;
			$classement = ClassementListeDAO::getInstance();
			
			// race
			$classement->races($choix_race);
			
			//grade?
			$classement->grade($triGrade);
			
			//type
			$classement->type($typeClassement);
			
			//date
			$param['timestamp'] = $classement->date($param['timestamp']);		

						
			//pagination				
			$classement->nombreParPage($param['nb_el']);
			
			// On prépare le classement pour pouvoir compter les lignes
			$classement->prepareClassement();
			
			$param['page_max'] = $classement->pagesMax();
			$classement->page($param['page']);	
			
			$param['first_date'] = $classement->first_date;
			
			// Recherche du matricule
			if(isset($_GET['search'])) {
			
				$highlight = $_GET['search'];
				$persos = \persos\PersosDAO::getInstance();	

				if(!is_numeric($_POST['search'])) {
					$p = $persos->SelectPersoByName($_GET['search']);
					$highlight = $p['id'];

				}		
				
				$rang = $classement->cherchePositionMat($highlight);

				//$param['page'] = $rang;
			} 
			if(isset($_POST['search'])) {
				$param['highlight'] = $_POST['search'];
				$persos = \persos\PersosDAO::getInstance();
				if(!is_numeric($_POST['search'])) {
					$p = $persos->SelectPersoByName($_POST['search']);
					if($p) {
						$param['highlight'] = $p['id'];
					}
				}
				$classement->cherchePositionMat($param['highlight']);
				//$param['page'] = $rang;
			}		
				
					
				
			// On prépare à nouveau le classement, avec le bon nombre de pages
			$classement->prepareClassement();
			
			// On récupère la page effective, et calcule l'indice de position
			$param['page'] = $classement->page();
			$param['startposition'] = ($param['page']-1)*$param['nb_el'];
			
			
			$result = $classement->retourneClassement();	

			afficheSelectionClassement($param);
			
			afficheNavigationClassement($param);
			
			afficheListeClassement($result , $param);
			
			afficheNavigationClassement($param);
			

//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
