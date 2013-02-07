<?php

namespace messagerie;

/**
 * Exécute une action sur une liste
 * 
 * Cette page permet d'envoyer un bal depuis un appel ajax en passant les données en POST
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
 
require_once __DIR__ . '/../conf/master.php';


if(isset($_POST['mat'])){
	$id_perso = $_POST['mat'];
} else {
	$id_perso = $_SESSION['persos']['id'][1];
}

$_SESSION['perso']['id'] = $id_perso;
$utilisateur_id = $_SESSION['utilisateur']['id'];

if(isset($_POST['action']) && isset($_POST['id']) && (isset($_POST['membre']) || ($_POST['action'] != 'add' && $_POST['action'] != 'renvoi'))) {
	
	$id = substr(($_POST['id']),2);
	$action = $_POST['action'];

	$conn = messagerieDAO::getInstance();

		if($res = $conn->VerifPersoExisteUtilisateur($id_perso, $utilisateur_id)){
		
		if(!is_numeric($id)) {
			echo 'ko'; exit;
		}
		
		$liste = $conn->SelectInfoListeNumerique($id);
		
		if($action == 'quit' && $conn->IsOnListe($liste[0]['id'], $id_perso)) {
			if($liste[0]['owner'] == $id_perso) {
				// On est le proprio, on supprime la liste
				$conn->DeleteListe($liste[0]['id']);
				echo 'ok'; exit;
			} else {
				// On quitte la liste
				$conn->QuiteListe($liste[0]['id'],$id_perso);
				echo 'ok'; exit;
			}
		}elseif($action == 'supp') {
			if($liste[0]['owner'] == $id_perso) {
				// On est le proprio, on supprime la liste
				$conn->DeleteListe($liste[0]['id']);
				echo 'ok'; exit;
			}		
		} elseif($action == 'add') {
			$membre = $_POST['membre'];
			$conn->AddMatOnListe($id, $membre);
			echo 'ok'; exit;
		} elseif($action == 'renvoi') {
			$membre = substr(($_POST['membre']),3);
			$conn->QuiteListe($liste[0]['id'],$membre);
			echo 'ok'; exit;
		}
	}
}
echo 'ko';