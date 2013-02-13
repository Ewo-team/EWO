<?php
include_once('formatter.php');

class entraine extends formatter{
	public function printPublic(&$bdd){
		return 's\'<b>entra&icirc;ne</b> un peu';
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		$xp = ((int)parent::getEvent()->getSrc() === (int)$_SESSION['persos']['current_id'] || !isset($private['xpD']))?$private['xpA']:$private['xpD'];
		return 'Vos gains: '.$xp.' XP';
	}
	public function printGeneral(&$bdd){
		return 's\'<b>entrain&eacute;(e)</b> <i>plusieurs</i> fois';
	}
	public function getBackground(){
		return '#CCDDCC';
	}
}
