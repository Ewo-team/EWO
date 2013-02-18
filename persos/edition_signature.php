<?php	
require_once __DIR__ . '/../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if ((isset($_POST['signature'])) AND (isset($_POST['id_perso']))){

$signature = mysql_real_escape_string($_POST['signature']);
$id_perso = mysql_real_escape_string($_POST['id_perso']);
$utilisateur_id = $_SESSION['utilisateur']['id'];

	if(id_utilisateur($id_perso,$utilisateur_id) != false){
	mysql_query("UPDATE persos SET signature = '$signature' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'");
	 	$titre = "Modification de votre personnage";
		$text = "Votre signature a bien été mis à jour.";
		$lien = "../persos/editer_perso.php?id=".$id_perso."";
		gestion_erreur($titre, $text, $lien);		
	}else{
	 	$titre = "Modification de votre personnage";
		$text = "Votre message n'a pu etre mis à jour, ce personnage ne vous appartient pas'.";
		$lien = "/";
		gestion_erreur($titre, $text, $lien);
	}
   
}else{
 	$titre = "Modification de votre personnage";
	$text = "Vous n'êtes pas autorisé à effectuer cette action.";
	$lien = "../persos/editer_perso.php?id=".$id_perso."";
	gestion_erreur($titre, $text, $lien);
}
?>
