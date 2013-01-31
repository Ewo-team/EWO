<?php

namespace persos\annuaire;

/**
 * Fonction de recherche en fonction du nom du personnage
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT."/template/header_new.php");
	
//------------
if (isset($_POST['matricule']) AND is_numeric($_POST['matricule'])){
$mat = $_POST['matricule'];

	$conn = AnnuaireDAO::getInstance();
	
	$personnage = $conn->SelectPersoById($mat);	

	
	if ($personnage != NULL){

		//-- fiche personnage avec info minimaliste visible du publique.

		$nom      = $personnage['nom_perso'];
		$couleur  = $personnage['couleur'];	
		
		$pseudo = $personnage['nom_perso'];
		
		$url = icone_persos($mat);
		
		$_SESSION['rechercher']['pseudo'] = $nom;
		$_SESSION['rechercher']['matricule'] = $mat;
		
		include ("affiche_resultat.php");
		
		include( SERVER_ROOT ."/template/footer_new.php");exit;
	}else{
		$titre = "Erreur de matricule";
		$text = "Ce matricule n'existe pas, ou erreur de matricule.";
		$lien = "../annuaire";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien,1);
	}
}else{
	$titre = "Erreur de matricule";
	$text = "Un matricule vide n'est pas un matricule !";
	$lien = "../annuaire";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien,1);
}
?>
