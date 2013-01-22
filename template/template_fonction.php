<?php
/**
 * Chargement de la template et des fonctions associée
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template
 */
//--------- Insertion des includes de base pour EWO
include ($root_url."/conf/master.php");

$debut = getmicrotime();
//-- Template chargé : 1 oui; sinon n'existera pas;
$template_on = 1;

	$ewo_bdd = bdd_connect('ewo');
	
	if(!isset($_SESSION['header'])){
		$_SESSION['header'] = 'on';
	}
	
	if (!$ewo_bdd){
	echo "Nous sommes désolés, la base de données du jeu est actuellement hors service.";exit;
	}
?>
