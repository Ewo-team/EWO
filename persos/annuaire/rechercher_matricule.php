<?php

namespace persos\annuaire;

/**
 * Fonction de recherche en focntion du matricule
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
//-- Header --

require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT."/template/header_new.php");

//include 'AnnuaireDAO.php';
//------------
if (!empty($_POST['personnage'])){
$pseudo = $_POST['personnage'];

	$conn = AnnuaireDAO::getInstance();
	
	$personnage = $conn->SelectPersoByName($pseudo);

	if ($personnage != NULL){

		//-- fiche personnage avec info minimaliste visible du publique.

		$mat = $personnage['id_personnage'];

		$couleur  = $personnage['couleur'];	

		$url = icone_persos($mat);	
		
		$_SESSION['rechercher']['pseudo'] = $pseudo;
		$_SESSION['rechercher']['matricule'] = $mat;
	
		include ("affiche_resultat.php");
		include(SERVER_ROOT."/template/footer_new.php");
	}else{
		$titre = "Erreur de pseudo";
		$text = "Ce personnage n'existe pas, ou erreur de pseudo.";
		$lien = "../annuaire";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien, 1);
	}
}else{
$titre = "Erreur de pseudo";
$text = "Un pseudo vide n'est pas un pseudo !";
$lien = "../annuaire";
$root = "..";
gestion_erreur($titre, $text, $root, $lien,1);
}
?>
