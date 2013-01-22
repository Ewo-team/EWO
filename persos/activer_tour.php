<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

include("../persos/fonctions.php");
include("../jeu/fonctions.php");

if(isset($_GET['perso_id'])){
	$id = mysql_real_escape_string($_GET['perso_id']);
	if(isset($_SESSION['persos']['id'][$id])){
		$perso_id = $_SESSION['persos']['id'][$id];
		if(activ_tour($id)){
			$id = $_GET['perso_id'];
			$perso_id = $_SESSION['persos']['id'][$id];
			$nouveautour = $_SESSION['persos']['date_tour'][$id];
		//-- Affichage de l'avertissment de mise a jour
			echo "<h2>Votre Tour de jeu</h2><p align='center'>Votre tour a &eacute;t&eacute; mis &agrave; jour, votre prochain tour sera le : $nouveautour</p><p align='center'>[<a href='./liste_persos.php'>Retour</a>]</p>";
		}else{
			echo "<h2>Votre Tour de jeu</h2><p align='center'>Ce n'est pas le moment de refresh votre tour de jeu</p><p align='center'>[<a href='./liste_persos.php'>Retour</a>]</p>";
		}
	}else{
		$titre = "Erreur";
		$text = "Vous n'&ecirc;tes pas le propri&eacute;taire de ce personnage.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien, 1);
	}
}else{
	$titre = "Erreur";
	$text = "Vous n'&ecirc;tes pas autoris&eacute; &agrave; effectuer cette action.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien, 1);
}
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
