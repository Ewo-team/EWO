<?php
/**
 * Fonction de recherche en fonction du nom du personnage
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
include ("AnnuaireDAO.php");
	
//------------
if (isset($_POST['matricule']) AND is_numeric($_POST['matricule'])){
$mat = mysql_real_escape_string($_POST['matricule']);

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
		
		include($root_url."/template/footer_new.php");exit;
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
