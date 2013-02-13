<?php

include_once(SERVER_ROOT.'/jeu/fonctions.php');
include_once(SERVER_ROOT.'/persos/eventManager/special.php');

class formatter extends \persos\eventManager\tools {

	/*
	 * fichier de définition des etats d'un event
	 * 0 = echec
	 * 1 = reussite
	 * 2 = esquive
	 * 3 = echec critique
	 * 4 = reussite critique
	 * 5 = mort
	 */

	protected static $stateEvent = array(0=>'R&eacute;ussite',1=>'Echec',2=>'Esquive',
	3=>'Echec critique', 4=>'R&eacute;ssite critique', 5=>'Mort');
	private $event;

	public function __construct(&$event){
		if(isset($event) && is_object($event) && $event != NULL){
			$this->event = $event;
		}
	}

	public function printGeneral(&$bdd){
		return 'Ev&egrave;nements multiples';
	}
	public function printPublic(&$bdd){
		return 'Ev&egrave;nement public';
	}
	public function printPrivate(&$bdd){
		return 'Ev&egrave;nement priv&eacute;';
	}
	public function getBackground(){
		return '#FFFFFF';
	}
	public static function getState($key){
		if(isset(self::$stateEvent[$key]))
		return self::$stateEvent[$key];
		else
		return NULL;
	}
	protected function getEvent(){
		return $this->event;
	}
}

