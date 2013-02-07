<?php

namespace persos\annuaire;

/**
 * Ajoute un contact a l'annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */

//-- Header --
require_once __DIR__ . '/../../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

//print_r($_POST);exit;

$conn = AnnuaireDAO::getInstance();

if (isset($_POST['contact']) AND isset($_POST['personnage'])){

	$perso = $_POST['personnage'];
	$contact = $_POST['contact'];

	$exist = $conn->persoExist($contact);

	if($conn->persoExist($contact)){
	
		if($conn->AddPersoToRepertoire($perso, $contact)==1) {
			$titre = "Votre répertoire";
			$text = "Votre nouveau contact a bien été ajouté dans votre répertoire.";
			$lien = "../annuaire/";
			$root = "..";
			gestion_erreur($titre, $text, $root, $lien);			
		} else {
			$titre = "Votre répertoire";
			$text = "Vous avez déjà ce contact dans votre répertoire.";
			$lien = "../annuaire/";
			$root = "..";
			gestion_erreur($titre, $text, $root, $lien);			
		}
	} else {
	
		$titre = "Votre répertoire";
		$text = "Votre contact n'existe pas.";
		$lien = "../annuaire/";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);		
	
	}

} else if(isset($_GET['id_contact']) AND isset($_GET['id_perso'])) {

	$perso = $_GET['id_perso'];
	$contact = $_GET['id_contact'];
	
	$exist = $conn->persoExist($contact);

	if($conn->persoExist($contact)){
	
		if($conn->AddPersoToRepertoire($perso, $contact)==1) {
			$titre = "Votre répertoire";
			$text = "Votre nouveau contact a bien été ajouté dans votre répertoire.";
			$lien = "../annuaire/";
			$root = "..";
			gestion_erreur($titre, $text, $root, $lien);			
		} else {
			$titre = "Votre répertoire";
			$text = "Vous avez déjà ce contact dans votre répertoire.";
			$lien = "../annuaire/";
			$root = "..";
			gestion_erreur($titre, $text, $root, $lien);			
		}
	} else {
	
		$titre = "Votre répertoire";
		$text = "Votre contact n'existe pas.";
		$lien = "../annuaire/";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);		
	
	}
}
?>
