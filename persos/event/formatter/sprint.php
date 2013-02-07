<?php
include_once('formatter.php');
class sprint extends formatter{
	private $backg='background-color:#FFFFFF;';

	public function printPublic(&$bdd){
		return 'pousse un petit <b>sprint</b>';
	}
	
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		return 'Vos gains: '.$private['xp'].' XP';
	}
	public function getBackground(){
		return '#ACDCCC';
	}
}