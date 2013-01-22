<?php
/**
 * Compte, Edition vacance
 *
 *	Action pour envoyer ou ramener un joueur de vacances
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category vacance
 */

require_once('fonctions.php');
session_start();
//-- Header --
$root_url = "..";
include($root_url."/conf/master.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];

if (!empty($_POST['action']) && !empty($_POST['check_vacances'])){
	
	$action = $_POST['action'];
	
	$statut = statutVacances($id_utilisateur);
	if(false === $statut){
		erreurVacances();
	}
	else{
		switch($action){
			case 'depart' : 
				//Pour partir il faut être en jeu
				if($statut != 'jeu'){
					erreurVacances();
				}
				else{
					departVacances($id_utilisateur);
				}
				break;
			case 'retour' : 
				//Pour revenir il faut être en vacances
				if($statut != 'vacances'){
					erreurVacances();
				}
				else{
					retourVacances($id_utilisateur);
				}
				break;
			default : 
				erreurVacances();
		}
		header("location:../compte/options.php");exit;
	}
}else{
	erreurVacances();
}


function erreurVacances(){
	$titre = "Vacances";
	$text = "Vous n'êtes pas autorisés à effectuer cette action.";
	$lien = "../compte/options.php";
	$root = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
