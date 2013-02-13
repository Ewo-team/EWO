<?php
include_once('formatter.php');

class mouv extends formatter{
	function printPublic(&$bdd){
		return 's\'est <b>d&eacute;plac&eacute;(e)</b>';
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		return 'en '.$private['x'].'/'.$private['y'].' ('.$private['p'].')';
	}
	public function printGeneral(&$bdd){
		return 's\'est <i>beaucoup</i> <b>boug&eacute;(e)</b>';
	}
	public function getBackground(){
		return '#FFFFFF';
	}
}
