<?php

include_once('dataEvents.php');

//Fonction de sérialisation

function serievent($array){
$retour = '';
foreach($array as $key => $value){
	$retour=$retour.$key.'|'.mysql_real_escape_string($value).'|';
	}
return $retour;
}

function unserievent($seriarray){
$explode = explode('|', $seriarray);
$retour = array();
$nb = count($explode)-1;
for($inci=0; $inci<$nb;$inci+=2){
	$key = $explode[$inci];
	$value = $explode[$inci+1];
	$retour[$key]=$value;
	}
return $retour;
}

/**
 * Ô toi qui veut ajouter un évènement va
 * prestement voir la méthode "addEvent"
 * @name Cette classe permet de gérer les évènements du joueur
 * @author salelodenouye pour la EWO-Team
 *
 */
class EventsManager{

	// On déclare les variables...
	private $typeEvent;
	private $eventScheme;
	private $userID;
	private $eventData;

	/**
	 * Constructeur de la classe.
	 * On définit l'id de l'utilisateur à l'origine de l'évènement et le type d'évènement
	 * @param int $userId
	 * @param varchar $typeEvent
	 * @return true or false
	 */
	public function __construct($userId = null,$typeEvent = null){

		// Initialisation des données de l'évènement.
		$this->eventData = array();
		$this->dataLayer = new DataEvents();

		// Si l'UserID est défini, soit on veut écrire soit on veut lire...
		if($userId !== null){

			// S'il existe, on récupère le schéma de données de l'évènement sinon null
			$this->eventScheme = $this->getEventsScheme($typeEvent);

			// Si typeEvents est non null, on veut probablement écrire un évènement...
			if($this->eventScheme !== null){

				// On récupère le nom de l'évènement
				$this->typeEvent['name'] = $typeEvent;

				// On récupère l'ID de l'utilisateur qui provoque l'évènement (on s'occupera des victimes plus tard...)
				$this->userID = $userId;
				
				return true;
			}
			// Sinon peut être qu'on veut récupérer les évènement de quelqu'un ??
			else{
				// @TODO lire les évènements...
			}
			// (EPIC)FAIL l'event ne peut pas démarrer...(pas d'ID et/ou évènenement inconnu)
			return false;
		}

	}

	/**
	 *
	 * Cette fonction privée permet de définir pour chaque type d'évènement un schéma particulier...
	 * ...cette structure est également à utiliser pour écrire les données..
	 * il faut la mettre à jour dès qu'on ajoute un nouveau type d'évènement.
	 * et pour les récupérer.... :
	 * @param $type text
	 * @return array
	 */
	private function getEventsScheme($type){

		/**
		 * Les types d'évènements existants sont :
		 * enum(
		 * 'mouvement',		'attaque',	'esquive',		'sort',	'esquive_magique',
		 * 'sprint',		'suicide',	'entrainement',	'transaction',	'mort',
		 * 'meurtre',		'grade_up',	'grade_down',	'faction_in',
		 * 'faction_out',	'faction_eject',			'perso')
		 */
		$eventScheme = null;
		switch($type){
			case 'mouvement':
				$schemeID    = 1;
				$eventScheme = array('field','x','y');
				break;
			case 'attaque':
			  $schemeID    = 13;
				$eventScheme = array('exp_att', 'exp_def','att','def','degats','cible', 'cible_type', 'attaquant', 'meurtre');
				break;
			case 'esquive':
				$schemeID    = 2;
				$eventScheme = array('exp','att','def','MatriculeAttaquant','degats');
				break;
			case 'sort':
			  $schemeID    = 14;
				$eventScheme = array('exp_att', 'exp_def','attaquant','reussite','l_perso_vict','l_os_vict', 'l_oc_vict', 'l_p_vict', 'l_b_vict','l_perso_mort','l_os_det', 'l_oc_det', 'l_p_det', 'l_b_det','liste_Esquive','sort');
				break;
			case 'esquive_magique':
				$schemeID    = 3;
				$eventScheme = array('exp','attaquant','sort');
				break;
			case 'sprint':
				$schemeID    = 4;
				$eventScheme = array();
				break;
			case 'suicide':
				$schemeID    = 5;
				$eventScheme = array('Attaquant','px_pi');
				break;
			case 'entrainement':
				$schemeID    = 6;
				$eventScheme = array('cible','px_pi');
				break;
			case 'transaction' :
				$schemeID    = 7;
				$eventScheme = array('objet');
				break;
			case 'mort':
				$schemeID    = 8;
				$eventScheme = array('exp','att','def','attaquant', 'type_mort', 'type_attaquant', 'liste_effet');
				break;
			case 'meurtre':
				$schemeID    = 9;
				$eventScheme = array('nombre_mort','liste_victime');
				break;
			case 'destruction':
				$schemeID    = 18;
				$eventScheme = array('nombre_objet','l_os_det', 'l_oc_det', 'l_p_det', 'l_b_det');
				break;
			case 'grade_up':
				$schemeID    = 15;
				$eventScheme = array('grade');
				break;
			case 'grade_down':
				$schemeID    = 10;
				$eventScheme = array('grade');
				break;
			case 'faction_in':
				$schemeID    = 16;
				$eventScheme = array();
				break;
			case 'faction_out':
				$schemeID    = 17;
				$eventScheme = array();
				break;
			case 'faction_eject':
				$schemeID    = 11;
				$eventScheme = array();
				break;
			case 'perso':
				$schemeID    = 12;
				$eventScheme = array();
				break;
		}
		if($eventScheme !== null){
			// On valorise l'id du schéma
			$this->typeEvent['id'] = $schemeID;
			// Allez hop on renvoit le schéma 
			return $eventScheme;
		}
		// Type d'évènement inconnu...
		return null;
	}
	
	
	/** 
	 * colateralEvent
	 * Permet de détecter un évènement provoqué indirectement et de faire les inscriptions nécessaires.
	 * -> Notamment pour les attaqués par exemple.
	 * @return unknown_type
	 */
	private function colateralEvent(){
	//TODO	
	}

	/**
	 *
	 * On récupère les évènement Publics
	 * @return array()
	 */
	public function getPublicEvents($idList,$filters){
			$eventsList = $this->dataLayer->getEvent($idList,$filters,true);
		foreach($eventsList as $key => $value){
			// Pour les évènements public, on a pas besoin des détails ^^
			unset($eventsList[$key]['champs']);
		}
		return($eventsList);
		
		
	}

	/**
	 *
	 * On récupère les évènements Privés
	 * @return array()
	 */
	public function getPrivateEvents($idList,$filters){
		$eventsList = $this->dataLayer->getEvent($idList,$filters,true);
		foreach($eventsList as $key => $value){
			// On recompose le tableau de données...
			$eventsList[$key]['champs'] = unserievent($value['champs']);
		}
		return($eventsList);
	}
	
	/**
	 * permet d'ajouter un évènement dans la bdd
	 * C'est la couche à appeler pour mettre à jour les évènements
	 * @param text $name
	 * @param text $data
	 * @return boolean
	 */
	public function addEvent($name,$data){
		// Si on a pas de schéma, on va éviter de faire une boucle sur du vide...
		if(!is_array($this->eventScheme)) return false;

		// TODO gérer l'ajout des évènements
		foreach($this->eventScheme as $key){
			if($name == $key){
				// on test que la clé n'a jamais été valorisée...
				if(!isset($this->eventData[$key])){
					$this->eventData[$key] = $data;
					return true;
				}
				// la clé a déjà été valorisée...echec de l'ajout de la donnée...
				return false;
			}
		}
		return false;
	}

	/**
	 * Si l'évènement est complet, on l'envoi à la bd
	 * Enter description here...
	 * @return unknown_type
	 */
	public function commitEvent(){

		/*/ ajouter une étoile pour voir les données de test...
		echo '<br><b>données recoltées pour les events de '.$this->userID.' : </b><br>';
		print_r($this->eventData);
		/**/
		
		if(is_array($this->eventData)){
			$test = serievent($this->eventData);
			//print_r(unserievent($test));
			//echo "<br/>";
			//echo "<br/>";
			$data = mysql_real_escape_string(strip_tags(serievent($this->eventData)));
			$commit = $this->dataLayer->addEvent($this->userID,$this->typeEvent['id'],$data);
		}
		return $commit;
	}


}
