<?php

namespace jeu;

require_once __DIR__ . '/../conf/master.php';


if (isset($_GET['perso_id'])) {
	if (($_GET['perso_id'] <= $_SESSION['persos']['inc']) && ($_GET['perso_id'] >= 1)) {
		$inc = filter_input(INPUT_GET, 'perso_id', FILTER_SANITIZE_NUMBER_INT);
		$perso_id = $_SESSION['persos']['id'][$inc];
		$id = $inc;
	} else {
		echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
		exit;
	}
} else {
	echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
	exit;
}

//$id = $_SESSION['persos']['id'][0];

$lock = rand();
$_SESSION['persos']['mouv_lock'][$id] = $lock;


include_once(SERVER_ROOT.'/persos/eventManager/special.php');

/*-- Connexion requise --*/
ControleAcces('utilisateur',1);
/*-----------------------*/

include_once(SERVER_ROOT.'/persos/fonctions.php');
include_once(SERVER_ROOT.'/jeu/fonctions.php');

// Param�tres de connexion � la base de donn�es
$ewo_bdd_connect = bdd_connect('ewo');




//$perso_id = $_SESSION['persos']['current_id'];

$is_spawn=false;

$sql="SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
$resultat = mysql_query ($sql) or die (mysql_error());
if($pos = mysql_fetch_array ($resultat)){

	$is_spawn=true;

}else {
	header("location:./index.php?perso_id=".$id."#p");
	//echo "<script language='javascript' type='text/javascript' >document.location='./index.php?perso_id=$id#p'</script>";exit;
}

if($is_spawn){
	activ_tour($id);

        


	mysql_query('SET autocommit=0;');
	mysql_query("START TRANSACTION;");
	$caracs = calcul_caracs($perso_id);
	$_SESSION['persos']['pos_x'][$id] = $pos["pos_x"];
	$_SESSION['persos']['pos_y'][$id] = $pos["pos_y"];
	$_SESSION['persos']['carte'][$id] = $pos["carte_id"];
	if($caracs['mouv'] > 0){
		if($lock == $_SESSION['persos']['mouv_lock'][$id]) {
                        unset($_SESSION['persos']['mouv_en_cours'][$id]);

			maj_pos($id, $caracs);
        
		}
	}
        
	$caracs = calcul_caracs($perso_id);
	if ($caracs['pv'] <=0) {
		// Le perso est mort. L'event est ajouté dans le déplacement. Pas d'event = mort de raison inconnu (frappé pendant un mouv ?)
		desincarne($perso_id);
	}
	mysql_query('COMMIT;');
	mysql_query('SET autocommit=1;');
	mysql_close();
}

//print_r($_SESSION);
unset($_SESSION['persos']['mouv_lock'][$id]);
header("location:./index.php?perso_id=".$id."#p");
//echo "<script language='javascript' type='text/javascript' >document.location='./index.php?perso_id=$id#p'</script>";exit;

//mysql_close();
?>
