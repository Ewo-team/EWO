<?php

namespace jeu;
use \compte\Compte as Compte;

require_once(__DIR__ .'/../conf/master.php');

if (!isset($_SESSION['utilisateur']['id'])) {
    header("location:../index.php");
}
$template_mage = false;
$jeu = true;

$css_files = 'decors,damier';


$ewo = bdd_connect('ewo');

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
$compte = new Compte($_SESSION['utilisateur']['id']);

$statut_vacances = $compte->statutVacances();

if ($statut_vacances == 'vacances' || $statut_vacances == 'retour') {
	$titre = "Modification de compte";
	$text = "Vous ne pouvez pas accéder à cette page car vous êtes en vacances.";
	$lien = "../compte/options.php";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

$id_utilisateur = $_SESSION['utilisateur']['id'];

if (isset($_SESSION['utilisateur']['template_mage'])) {
	$template_mage = $_SESSION['utilisateur']['template_mage'];
	$grille = grille_damier($id_utilisateur);
} else $grille = 0;

$rose = rose_damier($id_utilisateur);

if (isset($_GET['perso_id'])) {
	if (($_GET['perso_id'] <= $_SESSION['persos']['inc']) && ($_GET['perso_id'] >= 1)) {
		$inc = mysql_real_escape_string($_GET['perso_id']);
		$_SESSION['persos']['current_id'] = $_SESSION['persos']['id'][$inc];
		$_SESSION['persos']['id'][0] = $inc;
	} else {
		echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
		exit;
	}
} else {
	echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
	exit;
}

include(SERVER_ROOT . '/persos/fonctions.php');
include(SERVER_ROOT . '/jeu/fonctions.php');

$caracs		= calcul_caracs();
$vision = max(1,$caracs["perception"]);

$val = 980+(($vision-6)*2+1)*(45+$grille);
$val = max($val,980);

/*if ($_SESSION['offset_width']<=$val+30) {
	$page = "width : ".($val+30)."px";
	$page_ = ($val+30)."px;";
} else {*/
	$page = "width : 100%";
	$page_ = "100%;";
/*}*/
        
if (isset($template_mage) && $template_mage == true) {
	$width = "style=\"width : ".$val."px\";";
	$width_page = "style='".$page.";'";
	$width_page_ = "width:".$page_;
	$width_content_jeu = "style=\"width : ".($val-20)."px\";";
	$width__ = "style=\"width : ".($val-300)."px\";";
} elseif (isset($template_vanilla) && $template_vanilla == true) {
	$width = "style=\"min-width : ".$val."px\";";
	$width_page = "style='".$page.";'";
	$width_page_ = "min-width:".$page_;
	$width_content_jeu = "style=\"min-width : ".($val-20)."px\";";
	$width__ = "style=\"min-width : ".($val-300)."px\";";
} else {
	$width = "";
	$width_page = "";
	$width_page_ = "";
	$width_content_jeu = "";
	$width__ = "";
}

include(SERVER_ROOT."/template/header_new.php");

// Recupération des infos issues de la dernière action effectuée.
if (isset($_SESSION['temp']['info_action'])) {
	$info_action = $_SESSION['temp']['info_action'];
	$_SESSION['temp']['info_action']=NULL;
} else $info_action = "";

activ_tour($_SESSION['persos']['id'][0]);

// Recupération des données
$perso_id = $_SESSION['persos']['current_id'];
$id = $_SESSION['persos']['id'][0];
$race_grade = recup_race_grade($perso_id);
$race		= $race_grade['race_id'];
$grade		= $race_grade['grade_id'];
$galon		= $race_grade['galon_id'];
$_SESSION['persos']['galon'][$inc] 			= $race_grade['galon_id'];

$is_spawn = false;

$sql = "SELECT mortel FROM persos WHERE id='$perso_id'";
$resultat = mysql_fetch_array(mysql_query ($sql)) or die (mysql_error());
$mortel = $resultat['mortel'];
$_SESSION['persos']['mortel'][$id] = $mortel;

$sql = "SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
$resultat = mysql_query ($sql) or die (mysql_error());
if ($pos = mysql_fetch_array ($resultat)) {

	if (($race==3 && $pos['carte_id']!=3)|| ($race==4 && $pos['carte_id']!=2)) {
		raz_alter_plan($perso_id);
	}

	$is_spawn=true;

} else {
    if($mortel == 1) {
        // Le perso mortel est désincarné == il est mort, on le passe à -1 et on retourne une erreur
        mysql_query("UPDATE persos SET mortel = -1 WHERE id='$perso_id'") or die (mysql_error());
        $_SESSION['persos']['mortel'][$id] = -1;
        $titre = "R.I.P.";
	$text = "Votre personnage est mort de sa mort définitive. Désolé.";
	$lien = "../persos/liste_persos.php";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
    }
    if (isset($_POST['respawn'])) {
            $choix_spawn = '';
            if (isset($_POST['cible_spawn']) && is_numeric($_POST['cible_spawn'])) {
                    $choix_spawn=mysql_real_escape_string($_POST['cible_spawn']);
            }
            $pos = respawn($id, '', $choix_spawn);

            $is_spawn=true;
            
            if($mortel == 2) {
                    mysql_query("UPDATE persos SET mortel = 1 WHERE id='$perso_id'") or die (mysql_error());
                    $_SESSION['persos']['mortel'][$id] = 1;
            }            

            activ_tour($_SESSION['persos']['id'][0], true);

            maj_alter_spawn($perso_id, 0);
    }
}

if ($is_spawn) {
	$caracs		= calcul_caracs();
	$vision = max(1,$caracs["perception"]);

	$_SESSION['persos']['pos_x'][$id] = $pos["pos_x"];
	$_SESSION['persos']['pos_y'][$id] = $pos["pos_y"];
	$_SESSION['persos']['carte'][$id] = $pos["carte_id"];

	maj_pos($id, $caracs);

	if (isset($_SESSION['temp']['info_action'])) {
		$info_action = $info_action."<br/>".$_SESSION['temp']['info_action'];
		$_SESSION['temp']['info_action']=NULL;
	}

	if (isset($_SESSION['action']['erreur'])) {
		$info_action = $info_action."<br/>".$_SESSION['action']['erreur'];
		$_SESSION['action']['erreur']=NULL;
	}


	$pos_x_perso = $_SESSION['persos']['pos_x'][$id];
	$pos_y_perso = $_SESSION['persos']['pos_y'][$id];
	$carte_pos = $_SESSION['persos']['carte'][$id];


	$sql = "SELECT * FROM cartes WHERE id='$carte_pos'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$carte = mysql_fetch_array ($resultat);

	$x_min_carte = $carte['x_min'];
	$x_max_carte = $carte['x_max'];

	$y_min_carte = $carte['y_min'];
	$y_max_carte = $carte['y_max'];
	
	$nom_decors = $carte['nom_decors'];

	$_SESSION['x_min_visible'] = $carte['visible_x_min'];
	$_SESSION['x_max_visible'] = $carte['visible_x_max'];

	$_SESSION['y_min_visible'] = $carte['visible_y_min'];
	$_SESSION['y_max_visible'] = $carte['visible_y_max'];

	$_SESSION['circ'][0] = $carte['circ'][0];
	$_SESSION['circ'][1]  = $carte['circ'][1];

	if (($pos_y_perso-$vision)>($y_min_carte) || !$carte['circ'][1]) {
		$y_min = $pos_y_perso-$vision;
	} else {
		$y_min = $y_max_carte + ($pos_y_perso-$vision-$y_min_carte);
	}

	if (($pos_y_perso+$vision)<($y_max_carte) || !$carte['circ'][1]) {
		$y_max = $pos_y_perso+$vision;
	} else {
		$y_max = $y_min_carte + ($pos_y_perso+$vision-$y_max_carte);
	}

	if (($pos_x_perso-$vision)>($x_min_carte) || !$carte['circ'][0]) {
		$x_min    = $pos_x_perso-$vision;
	} else {
		$x_min    = $x_max_carte + ($pos_x_perso-$vision-$x_min_carte);
	}

	if (($pos_x_perso+$vision)<($x_max_carte) || !$carte['circ'][0]) {
		$x_max = $pos_x_perso+$vision;
	} else {
		$x_max = $x_min_carte + ($pos_x_perso+$vision-$x_max_carte);
	}

	$icone_perso = icone_persos($perso_id);
}

// Récupération de toutes les infos nécéssaires au fonctionnement des module damier et action.
// infos.php, damier.php et panel_actions.php peuvent ainsi être inclus dans n'importe quel ordre.
include("infos_damier.php");

// REcuperation des caracs
$perso_id 	= $_SESSION['persos']['current_id'];
$id			= $_SESSION['persos']['id'][0];

//-- Liste complete des caracteristiques
$race		= $_SESSION['persos']['race'][$id];
$grade		= $_SESSION['persos']['grade'][$id];
$camp		= $_SESSION['persos']['camp'][$id];

$galon 		= $_SESSION['persos']['galon'][$id];
$affil 		= $_SESSION['persos']['superieur'][$id];

$caracs_max = caracs_base_max ($perso_id, $race, $grade);

if (isset($_POST['maj_des'])) {
	$val_att=$_POST['des_attaque'];
	if ($val_att>=$caracs_max['des']) {
		$val_att = $caracs_max['des']-1;
	}
	maj_carac($perso_id, "des_attaque", $val_att);
}

$caracs = calcul_caracs();

//-- Index jeux --
include(SERVER_ROOT."/template/index_jeux.php");
//------------
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
