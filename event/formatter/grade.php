<?php
include_once('formatter.php');

class grade extends formatter{
	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			// renier
			case 2:
				return 'a &eacute;t&eacute; reni&eacute;(e) par:';
			// desaffilier
			case 1:
				return 's\' d&eacute;affili&eacute;(e) de:';
			// affilier
			case 0:
			default:
				return 's\'<b>affili&eacute;(e)</b> &agrave;:';
		}
	}
	// nom + race
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		return '';
	}
}
