<?php
/**
 * Envoie un bal
 * 
 * Cette page permet d'envoyer un bal depuis un appel ajax en passant les données en POST
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
include($root_url."/mail/mail.php");
include ("messagerieDAO.php");
/*-- Connexion requise --*/
if (ControleAcces('utilisateur',0) == false){
	echo "acces denied";exit;
}
/*-----------------------*/
$ajax = false;
if(isset($_POST['ajax'])) {
	$ajax = true;
}

$droits = $_SESSION['utilisateur']['droits'];

if ((!empty($_POST['mat']) || !empty($_POST['liste'])) AND !empty($_POST['text']) AND !empty($_POST['matperso'])){
		
	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();

	$matperso = $_POST['matperso'];
	if($conn->VerifPersoExisteUtilisateur($matperso, $_SESSION['utilisateur']['id'])) { 
		$expediteur = $matperso;
	} else {
		if($ajax) {
			echo "erreur"; exit;
		} else {
			$titre = "BAL";
			$text = "Une erreur s'est produite";
			$lien = "../messagerie/index.php?id=".$matperso;
			$root = "..";
			gestion_erreur($titre, $text, $root, $lien);	
		}
	}
	if(!empty($_POST['liste']) && $_POST['liste'] != 'null') {
		//echo "liste: ".$_POST['liste'];
		$id_courant = array_search($matperso, $_SESSION['persos']['id']);
		$matricule = array();
		$camp = $_SESSION['persos']['camp'][$id_courant];
		if(is_numeric($_POST['liste'])) {
			$matricule = $conn->SelectMatriculesBallingNumerique($_POST['liste'], $camp, $matperso);
		} else {
			switch($_POST['liste']) {
				case 'faction': 
					if($_SESSION['persos']['faction']['id'][$id_courant] != 0) {
						$droits = $_SESSION['persos']['faction']['droits'][$id_courant];
			
						if($droits[6] == 1 || $droits[0] == 1) {
							$matricule = $conn->SelectMatriculesBallingFaction($_SESSION['persos']['faction']['id'][$id_courant]);
						}
					}
					break;
				case 'plan':
					$grade = $_SESSION['persos']['grade'][$id_courant];
					$galon = $_SESSION['persos']['galon'][$id_courant];
					$planperso = $_SESSION['persos']['carte_respawn'][$id_courant];			
					if($_SESSION['persos']['faction']['id'][$id_courant] != 0) {
						$type = $_SESSION['persos']['faction']['type'][$id_courant];

						if($type == 2 && ($grade >= 3 || $galon >= 2)) {
							$balplan = 1;
						}				
					}
					if($grade == 5 || ($grade == 4 && $galon >= 2) || $balplan==1) {
						if(isset($_SESSION['persos']['carte'][$id_courant]) && $_SESSION['persos']['carte'][$id_courant] == $planperso && ($camp == 3 || $camp == 4)) {
							$matricule = $conn->SelectMatriculesBallingPlan($planperso);
						}
					}
					break;
				case 'admin':
				case 'anim':
				case 'at':
					$matricule = $conn->SelectMatriculesBallingSpecial($_POST['liste']);
					break;	
				case 'mass_joueur':
					if($droits[1] == 1) {
						$matricule = $conn->SelectMatriculesBallingJoueurs();
					} else {
						$titre = "BAL";
						$text = "Vous n'avez pas les droits nécessaires";
						$lien = "../messagerie/index.php?id=".$matperso;
						$root = "..";
						gestion_erreur($titre, $text, $root, $lien);						
					}
					break;
				case 'mass_camp':
					if($droits[2] == 1) {				
						$matricule = $conn->SelectMatriculesBallingCamp($expediteur);
					} else {
						$titre = "BAL";
						$text = "Vous n'avez pas les droits nécessaires";
						$lien = "../messagerie/index.php?id=".$matperso;
						$root = "..";
						gestion_erreur($titre, $text, $root, $lien);						
					}						
					break;
			}
		}
		$liste_bal = '';
		$maillinglist = $_POST['liste'];	
	} else {
		$matri = $_POST['mat'];
		$matricule =  preg_split('#/|\.|-#', $matri);
		$matricule = array_unique($matricule);
		$liste_bal = implode("-", $matricule);	
		$maillinglist = NULL;
	}

	$titre = htmlentities($_POST['titre'], ENT_COMPAT, 'UTF-8');
	$corps = urldecode($_POST['text']);
	
	$badmat = '';

	if(!$maillinglist) {
		foreach ($matricule as $mat){
			$res[0]=0;
			if(!preg_match('#[a-z]#i',$mat) && !(preg_match('#[^0-9]#',$mat) && preg_match('#[^a-z]#i',$mat)) && !empty($mat)){
				$res = $conn->VerifPersoId($mat);
			}	
			if(!$res['count']){
				$badmat .= $mat.' ';
			}
		}
	}

	if(empty($badmat) && count($matricule > 0)){

		$type_message = 'joueur';
		
		if(isset($_POST['type_message'])) {
			$droits = $_SESSION['utilisateur']['droits'];
			
			
			if($droits[1] == 1 && $_POST['type_message'] == 'admin') {
				$type_message = 'admin';
			}
			
			if($droits[2] == 1 && $_POST['type_message'] == 'anim') {
				$type_message = 'anim';
			}
			
			if($droits[3] == 1 && $_POST['type_message'] == 'at') {
				$type_message = 'at';
			}	
		}
		
		
		//-- Copie de la bal envoyé dans la table bals_send
		$sql_balsend = $conn->InsertBalSend($expediteur, $liste_bal, $titre, $corps, '1', $type_message, $maillinglist);

		//$liste_bal= substr($liste_bal, 0, -1);
		//$liste_bal .= $expediteur;
		
		$corps_id = $conn->InsertCorpsBal($titre, $corps, $liste_bal, $maillinglist);
		
		$sql_query = $conn->PrepareInsertBal('bals');		
		foreach ($matricule as $mat){

			if(empty($titre)){
				$titre = "Aucun titre";
			}
			$flag = '0';
			if($mat == $expediteur) {
				$flag = '1';
			}
			
			if(is_array($mat)) {
				$mat = $mat[0];
			}	
			
			if($mat != 0) {
				$parametres = array($expediteur, $mat, $corps_id, $flag, $type_message, 0);
				//-- Envoie de la bal a son destinataire
				$sql_bal = $conn->InsertBalPrepare($sql_query, $parametres);
			}
			/*
			$baltest = mail_defaut($mat);
			if($baltest['mail'] == 'true'){
				$type = $baltest['type'];
			
				//-- Gestion de l'envoi des mails
				$id_dest = id_utilisateur ($mat);
				$mail = array(mail_utilisateur($id_dest));
				$mail_nom = nom_perso($expediteur,true);
				$subject = "[Ewo] Vous avez reçu un bal de ".$mail_nom;

				//include($root_url."/mail/mail.php");

				if($type=='html'){
					$corps_s = "Vous avez recu un message venant de <a href='http://www.ewo-le-monde.com/messagerie/index.php?id=".$mat."'>".$mail_nom."</a><hr />".$corps;
				}elseif($type=='text'){
					$corps_s = "Vous avez reçu une bal dans votre boite de reception";		
				}
				send_mail($mail,$subject,$corps_s,$type);
			}	*/
		}

		//echo "ok"; //message bien envoyé
	} else {
		if(count($matricule) > 0) {
			if($ajax) {
				echo $badmat; exit; // matricule avec erreur
			} else {
				$titre = "BAL";
				$text = "Le(s) matricule(s) suivant(s) provoque une erreur : $badmat";
				$lien = "../messagerie/index.php?id=".$matperso;
				$root = "..";
				gestion_erreur($titre, $text, $root, $lien);	
			}
		} else {
			if($ajax) {
				echo 'erreur'; exit; // matricule avec erreur
			} else {
				$titre = "BAL";
				$text = "Une erreur s'est produite";
				$lien = "../messagerie/index.php?id=".$matperso;
				$root = "..";
				gestion_erreur($titre, $text, $root, $lien);	
			}
		}
		//echo 'badmat'; //message bien envoyé
	}
	
	if($ajax) {
		echo 'ok'; exit;  // matricule avec erreur
	} else {
		$titre = "BAL";
		$text = "Votre message à bien été envoyé";
		$lien = "../messagerie/index.php?id=".$matperso;
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);	
	}
	
} else {
	if($ajax) {
		echo 'erreur'; exit;  // matricule avec erreur
	} else {
		$titre = "BAL";
		$text = "Une erreur s'est produite";
		$lien = "../messagerie/";
		$root = "..";
		gestion_erreur($titre, $text, $root, $lien);	
	}
}
?>
