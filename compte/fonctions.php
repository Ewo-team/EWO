<?php

namespace compte;

/**
 * Compte, fonctions
 *
 *	Fonctions utiles pour la page d'option du compte
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category fonctions
 */	

$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$root_url = explode('/',$path);
array_pop($root_url);
$root_url = join('/',$root_url);

require_once('config_vacances.php');
include_once($root_url.'/persos/eventManager/eventManager.php');
include_once($root_url.'/persos/eventManager/eventFormatter.php');
	


function addEventVacances($matricule, $action = 1,$private_info=false){
	$evman = new EventManager();
	$evenement = $evman->createEvent('vacances');
	$evenement->setSource($matricule, eventFormatter::convertType('perso'));
	if(false !== $private_info){
		$evenement->infos->addPrivateInfo('xp',$private_info);
	}
	$evenement->setState($action);
}
	
?>
