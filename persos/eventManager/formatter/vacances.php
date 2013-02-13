<?php
include_once('formatter.php');
class vacances extends formatter{
	private $backg='background-color:#FFFFFF;';

	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			case '1' : return 's\'appr&ecirc;te &agrave; partir en vacances';break;
			case '2' : return 'est parti(e) en vacances';break;
			case '3' : return 'est revenu(e) de vacances';break;
			default : return '';
		}
		
	}
	
	public function printPrivate(&$bdd){
		if(parent::getEvent()->getState() == '3'){
			$private = parent::getEvent()->infos->getPrivateInfos();
			return 'Vos gains: '.$private['xp'].' XP';
		}
		else{
			return '';
		}
	}
	public function getBackground(){
		return '#ACDCCC';
	}
}