<?php	
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if ((isset($_POST['background'])) AND (isset($_POST['id_perso']))){

$background = mysql_real_escape_string($_POST['background']);
$id_perso = mysql_real_escape_string($_POST['id_perso']);
$utilisateur_id = $_SESSION['utilisateur']['id'];

	if(id_utilisateur($id_perso,$utilisateur_id) != false){
		mysql_query("UPDATE persos SET background = '$background' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'");
	 	$titre = "Modification de votre personnage";
		$text = "Votre background a bien été mis à jour.";
		$lien = "../persos/editer_perso.php?id=".$id_perso."";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);		
	}else{
	 	$titre = "Modification de votre personnage";
		$text = "Votre message n'a pu etre mis à jour, ce personnage ne vous appartient pas'.";
		$lien = "/";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);
	}
   
}else{
 	$titre = "Modification de votre personnage";
	$text = "Vous n'êtes pas autorisé à effectuer cette action.";
	$lien = "../persos/editer_perso.php?id=".$id_perso."";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
