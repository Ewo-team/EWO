<?php
include_once('formatter.php');
class attaque extends formatter{
	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			case 5:
				return 'a inflig&eacute; une peine de <b>mort</b>';
			case 4:
				return 'a provoqu&eacute; la <b>destruction</b>';
				break;
			case 1:
				return 'a r&eacute;ussi &agrave; <b>frapper</b>';
			case 0:
			default:
				return 'a vu son <b>attaque</b> <u>esquiv&eacute;e</u>';
		}
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		$deg=(isset($private['deg']))?$private['deg']:$private['degats'];
		$xp='';
		$res=parent::chkSrc(parent::getEvent()->getSrc(),parent::getEvent()->getDst());
		if($res){
			$xp = $private['xpA'];
		}elseif(!$res){
			$xp = $private['xpD'];
		}
		return 'Score : '.$private['att'].'/'.$private['def'].' | '.
		'D&eacute;g&acirc;ts ('.$deg.')<br/>'.
		(($xp!='')?'Vos gains: '.$xp.' XP':$xp);
	}

	public function printGeneral(&$bdd){
		return 'a lanc&eacute; <i>plusieurs</i> <b>attaques<b>';
	}

	public function getBackground(){
		switch(parent::getEvent()->getState()){
			case 5:
			case 4:
				return '#FFCCCC';
			case 1:
				return '#DDDDDD';
			case 0:
			default:
				return '#CCCCCC';
		}
	}
}
