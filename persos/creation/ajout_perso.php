<?php

require __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT. "/persos/fonctions.php");

include_once(SERVER_ROOT."/persos/creation/controle_persos.php");

ControleAcces('utilisateur',1);

if(!isset($_SESSION['CreationPerso']['Etape'])) {
	exit;
}

$controle = controleCreationPerso($utilisateur_id);
$etape = $_SESSION['CreationPerso']['Etape'];

if($etape >= 1) {
	// Verification de l'étape 1
	if(!isset($_POST['gameplay'])) {
		$_SESSION['erreur']['perso'] = "Veuiller choisir un gameplay.";
		header("location: .");		
	}
	
	if(!isset($_POST['race'])) {
		$_SESSION['erreur']['perso'] = "Veuiller choisir un camp.";
		header("location: .");		
	}	
	
	if($_POST['race'] != $controle['camp'] && $controle['camp'] != "") {
		$_SESSION['erreur']['perso'] = "Multicamps interdit!";
		header("location: .");				
	}
	
	if($_POST['race'] != 'ange' && $_POST['race'] != 'demon' && $_POST['race'] != 'humain') {
		$_SESSION['erreur']['perso'] = "Gest lost, kthxbyebbq.";
		header("location: .");		
	}
	
	if($_POST['gameplay'] == 'T3' && !$controle['creationT3']) {
		$_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer de perso type T1!";
		header("location: .");		
	}
	
	if($_POST['gameplay'] == 'T41' && !$controle['creationT4']) {
		$_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer de perso type T4!";
		header("location: .");		
	}	
	
	if($_POST['gameplay'] != 'T3' && $_POST['gameplay'] != 'T41') {
		$_SESSION['erreur']['perso'] = "Veuillez, monsieur, cesser de magouiller les variables du formulaire";
		header("location: .");		
	}	
	
	if(!$controle['peutCreer']) {
		$_SESSION['erreur']['perso'] = "La limite du nombre de persos sert à limiter le nombre de persos";
		header("location: .");		
	}

	$gameplay = $_POST['gameplay'];
	$race 	  = $_POST['race'];
	$_SESSION['CreationPerso']['Gameplay'] = $gameplay;
	$_SESSION['CreationPerso']['Race'] = $race;
	$_SESSION['CreationPerso']['Etape'] = 2;
}
/*
if (empty($_POST['nom']) OR empty($_POST['race'])) {
	$_SESSION['erreur']['perso'] = "Veuiller entrer un nom.";
	header("location:creation_perso.php");
}

// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');

include($root_url."/persos/fonctions.php");
include($root_url."/jeu/fonctions.php");
//include($root_url."/inscription/reservation.php");

if (!isset($admin_mode)) {
	$utilisateur_id = $_SESSION['utilisateur']['id'];
	include($root_url."/inscription/controle_persos.php");
} else {
	if (!isset($_POST['utilisateur_id'])) {
		$_SESSION['erreur']['perso'] = "Veuiller entrer un id utilisateur.";
		header("location:creation_perso.php");
	} else {
		$utilisateur_id = $_POST['utilisateur_id'];
	}
}

$perso_nom_fofo = ucfirst(htmlspecialchars(strip_tags($_POST['nom']),ENT_COMPAT, 'UTF-8'));
$perso_nom = mysql_real_escape_string(ucfirst(htmlspecialchars(strip_tags($_POST['nom']),ENT_COMPAT, 'UTF-8')));
$perso_race = $_POST['race'];

if(!is_numeric($perso_race)) {
	$_SESSION['erreur']['perso'] = "Veuillez choisir la race.";
	header("location:creation_perso.php");
	exit;
} else {
	if (isset($_POST['type']) && is_numeric($_POST['type'])) {
		$perso_type = $_POST['type'];
		if ($perso_type == 3 || $perso_type == 4) {
			$sql = "SELECT race_id FROM races WHERE camp_id = '$perso_race' AND type = '$perso_type' AND grade_id=-2";
			$resultat = mysql_query ($sql) or die (mysql_error());
			$info_race = mysql_fetch_array ($resultat);
			$race = $info_race['race_id'];
		} else {
			$_SESSION['erreur']['perso'] = "Erreur dans le choix du type de perso.";
			header("location:creation_perso.php");
			exit;
		}
	} else {
		$_SESSION['erreur']['perso'] = "Erreur dans le choix du type de perso.";
		header("location:creation_perso.php");
		exit;
	}
}
// On vérifi si le sexe est possible, homme/femme/autre (= 1/2/3)
$sexe = mysql_real_escape_string($_POST['sexe']);
if (!($sexe == 1 || $sexe == 2 || $sexe == 3)) {
	$_SESSION['erreur']['perso'] = "Sexe anormal !";
	header("location:creation_perso.php");
	exit;
}

// Si le combot camp + type existe pas, mysql revoi NULL
if ($race == NULL) {
	$_SESSION['erreur']['perso'] = "Ce type de perso n'existe pas dans ce camp.";
	header("location:creation_perso.php");
	exit;
}

// Verifie si l'utilisateur peut créer ce type de perso et si son camp est bon.
if (!isset($admin_mode)) {
	$sql = "SELECT races.camp_id, races.type FROM persos, races WHERE (persos.race_id = races.race_id AND races.grade_id = -2) AND persos.utilisateur_id = $utilisateur_id";
	$resultat = mysql_query ($sql) or die (mysql_error());

	$camp = NULL;
	$t3 = $t4 = 0;

	while ($perso = mysql_fetch_array($resultat)) {
		if (!$camp && $perso['camp_id'] != 2 && $perso['camp_id'] != 5 && $perso['camp_id'] != 6) {
			$camp = $perso['camp_id'];
		}

		if ($perso['type'] == 4)
			$t4++;
		else
			$t3++;
	}

	if ($camp == 1) {
		$restantT3 = 1 - $t3;
		$restantT4 = 8 - $t4;
		$groupeT4 = true;
	} else {
		if ($t4 >= 1) {
			$restantT3 = 2 - $t3;
			$restantT4 = 4 - $t4;
			$groupeT4 = true;
		} else {
			$restantT3 = 3 - $t3;
			if ($restantT3 >= 1)
				$restantT4 = 4;
			else
				$restantT4 = 0;
			$groupeT4 = false;
		}
	}

	if ($perso_type == 3) {
		if ($restantT3 < 1) {
			$_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer ce type de personnage.";
			header("location:creation_perso.php");
			exit;
		}
	} else { // =4
		if ($restantT4 < 1) {
			$_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer ce type de personnage.";
			header("location:creation_perso.php");
			exit;
		}
	}

	if ($camp != NULL && $camp != $perso_race) {
		$_SESSION['erreur']['perso'] = "On se limite aux personnages de son camp.";
		header("location:creation_perso.php");
		exit;
	}
}

//-- Recup du mail et pass de l'utilisateur pour l'injecter dans la bdd du forum PHPBB pour créer le compte du personnage.
$sql = "SELECT passwd_forum, email FROM utilisateurs WHERE id = '$utilisateur_id'";
$resultat = mysql_query ($sql) or die (mysql_error());
$user_compte = mysql_fetch_array ($resultat);

$utilisateur_mail = $user_compte['email'];
$utilisateur_pass = $user_compte['passwd_forum'];
//---------------------

$perso_bg = mysql_real_escape_string($_POST['bg_perso']);

// Vérifier que le nom ne soit pas en bdd
$verif_nom_existe = mysql_query("SELECT nom FROM persos WHERE nom = '$perso_nom'") or die (mysql_error());
if (mysql_fetch_row($verif_nom_existe)) {
	$_SESSION['erreur']['perso'] = "Ce nom de personnage existe déjà.";
	header("location:creation_perso.php");
	exit;
}
if (empty($perso_nom) || !ctype_alpha($perso_nom[0])){
	$_SESSION['erreur']['perso'] = "Veuillez donner un nom à votre personnage (sans caracteres ^$*%...).";
	header("location:creation_perso.php");
	exit;
}

$matricule = null;
// Vérification de la réservation, et récupération du matricule si necessaire
if(checkReservation($perso_nom,$utilisateur_mail,$matricule) == 0) {
	$_SESSION['erreur']['perso'] = "Ce nom est déjà réservé.";
	header("location:creation_perso.php");
	exit;
}

$sql = "SELECT DISTINCT camp_id FROM races WHERE race_id = '$race'";
$resultat = mysql_query ($sql) or die (mysql_error());
$info_race = mysql_fetch_array ($resultat);

$sql = "SELECT nom AS nom FROM races WHERE race_id = '$race' AND grade_id=-2";
$resultat = mysql_query ($sql) or die (mysql_error());
$nom_race = mysql_fetch_array ($resultat);
$nom_race = $nom_race['nom'];

$sql = "SELECT nom AS nom FROM races WHERE race_id = '$race' AND grade_id=0";
$resultat = mysql_query ($sql) or die (mysql_error());
$nom_grade = mysql_fetch_array ($resultat);
$nom_grade = $nom_grade['nom'];

//-- ID
$race_id = $race;
$camp = $info_race['camp_id'];
$grade_id = 0;


// Tout semble ok, on peut créer le perso dans la BDD.

//-- AVATAR ET GROUPE FORUM
if ($camp == 3) {
	$avatar = '../images/persos/ange/ang01.gif';
} elseif ($camp == 4) {
	$avatar = '../images/persos/demon/dem01.gif';
} elseif ($camp == 1) {
	$avatar = '../images/persos/humain/hum01.gif';
}

if(!$matricule) {
	$matricule = 'null';
}
$sql = "INSERT INTO persos (
			`id`, `background`, `description_affil`, `utilisateur_id`, `nb_suicide`, `race_id`,
			`superieur_id`, `grade_id`, `faction_id`, `nom`, `creation_date`, `date_tour`,
			`avatar_url`, `icone_id`, `galon_id`, `options`, `mdj`, `signature`, `sexe`)
		VALUES (
			$matricule, '$perso_bg', '', $utilisateur_id, '', $race_id,
			null, $grade_id, '', '$perso_nom', CURRENT_TIMESTAMP(), '',
			'', '', '', '0', '', '', '".$sexe."')";
$sql_perso = mysql_query($sql);

//-- Recup de l'id du perso
if($matricule == 'null') {
	$id_perso = mysql_insert_id();
} else {
	$id_perso = $matricule;
}

//-- Alteration des caractéristiques de base
$sql = "INSERT INTO `caracs_alter` (
			`perso_id`, `alter_pa`, `alter_mouv`, `alter_def`, `alter_att`,
			`alter_recup_pv`, `alter_force`, `alter_perception`, `nb_desaffil`, `alter_niv_mag`)
		VALUES (
			'$id_perso', '', '', '', '',
			'', '', '', '', '')";
$sql_carac_alter = mysql_query($sql);

//-- Caracteristique de base des races
$caracs_base = caracs_base ($race, 0);

$px = 0;
$pi = 0;
$pv = $caracs_base['pv'];
$recup_pv = $caracs_base['recup_pv'];
$malus_def = 0;
$niv = $caracs_base['magie'];
$mouv = $caracs_base['mouv'];
$pa = $caracs_base['pa'];
$des_attaque = floor($caracs_base['des']/2);
$force = $caracs_base['force'];
$perception = $caracs_base['perception'];
$res_mag = $caracs_base['res_mag'];

$sql = "INSERT INTO `caracs` (
			`perso_id`, `px`, `pi`, `pv`, `recup_pv`, `malus_def`,
			`niv`, `cercle`, `mouv`, `pa`, `pa_dec`,
			`des_attaque`, `maj_des`, `force`, `perception`,`res_mag`)
		VALUES (
			'$id_perso', '$px', '$pi', '$pv','$recup_pv', '$malus_def',
			'$niv', '', '$mouv', '$pa', '',
			'$des_attaque', '', '$force', '$perception', '$res_mag')";
$sql_perso_carac = mysql_query($sql);


$sql_perso_design = mysql_query("INSERT INTO blocks (unique_id, perso_id, block_id, column_id, order_id) VALUES
									('', '$id_perso', 'block-1', 'column-1', 0),
									('', '$id_perso', 'block-3', 'column-1', 1),
									('', '$id_perso', 'block-2', 'column-1', 2),
									('', '$id_perso', 'block-4', 'column-1', 3),
									('', '$id_perso', 'block-5', 'column-2', 2),
									('', '$id_perso', 'block-6', 'column-2', 1),
									('', '$id_perso', 'block-7', 'column-2', 0)");

if($sql_carac_alter == FALSE) {
	echo 'sql_carac_alter';
	exit;
}
if($sql_perso_carac == FALSE) {
	echo 'sql_perso_carac';
	exit;
}
if($sql_perso == FALSE) {
	echo 'sql_perso1';
	exit;
}
if($sql_perso_design == FALSE) {
	echo 'sql_perso2';
	exit;
} else {
	mysql_close($ewo_bdd);

	//-- Code phpBB pour la gestion du pass et du login
	define('IN_PHPBB', true);
	$phpEx = 'php';
	$phpbb_root_path = $root.'/forum/';

	require($root.'/forum/common.php');
	require($root.'/forum/includes/functions_user.php');
	//--

	$utilisateur_pass = phpbb_hash($utilisateur_pass);

	require($root.'/persos/binding_forum.php');
	
	if(!LierPerso($perso_nom_fofo,$grade_id,$camp,$utilisateur_pass)) {
		// Le perso n'existais pas, il est ajouté

		// set user data
		$user_row = array(
			'username'		=> $perso_nom_fofo,
			'user_password'	=> $utilisateur_pass,
			'user_email'	=> $utilisateur_mail,
			'group_id'		=> $binding[$camp][0],
			'user_type'		=> USER_NORMAL);

		// add user
		if (user_add($user_row) == false) {
			$_SESSION['erreur']['perso'] = "Il est possible que ce nom de personnage existe déjà.";
			echo "<script language='javascript' type='text/javascript' >document.location='./creation_perso.php'</script>";
			exit;
		}
	}

	$_SESSION['temp']['perso_nom'] = $perso_nom;
	$_SESSION['temp']['perso_race'] = $perso_race;
	$_SESSION['temp']['perso_bg'] = $perso_bg;
	$_SESSION['temp']['perso_avatar'] = $avatar;

	$_SESSION['persos']['inc']		+= 1;
	$inc = $_SESSION['persos']['inc'];
	$_SESSION['persos']['id'][$inc]				= $id_perso ;
	$_SESSION['persos']['nom'][$inc]			= $perso_nom ;
	$_SESSION['persos']['race'][$inc]			= $race_id ;
	$_SESSION['persos']['race']['nom'][$inc]	= $nom_race ;
	$_SESSION['persos']['grade'][$inc]			= $grade_id ;
	$_SESSION['persos']['grade']['nom'][$inc]	= $nom_grade ;
	$_SESSION['persos']['faction']['id'][$inc]	= 0 ;
	$_SESSION['persos']['date_tour'][$inc]		= 0 ;
	$_SESSION['persos']['type'][$inc]			= $perso_type;
	$_SESSION['persos']['camp'][$inc]			= $perso_race;

	$_SESSION['temp']['perso_inc'] = $inc;

	echo "<script language='javascript' type='text/javascript' >document.location='../persos/apercu_perso.php'</script>";exit;
}
*/

header("location: .");

?>
