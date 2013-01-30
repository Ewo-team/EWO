<?php
include_once('formatter.php');

class reparation extends formatter{
	public function printPublic(&$bdd){
		return 'a r&eacute;par&eacute;';
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		$xp = ((int)parent::getEvent()->getSrc() === (int)$_SESSION['persos']['current_id'] || !isset($private['xpD']))?$private['xpA']:$private['xpD'];
		return 'Vos gains: '.$xp.' XP';
	}
	public function printGeneral(&$bdd){
		return 'a r&eacute;par&eacute;';
	}
	public function getBackground(){
		return '#CCDDCC';
	}
}

