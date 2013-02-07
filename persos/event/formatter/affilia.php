<?php
include_once('formatter.php');

class affilia extends formatter{
    const AFFI_AFFILIER     = 0;
    const AFFI_DESAFFILIER  = 1;
    const AFFI_RENIER       = 2;

	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			// renier
			case affilia::AFFI_RENIER:
				return 'a &eacute;t&eacute; <b>reni&eacute;(e)</b> par:';
				// desaffilier
			case affilia::AFFI_DESAFFILIER:
				return 's\'est <b>d&eacute;saffili&eacute;(e)</b>';
				// affilier
			case affilia::AFFI_AFFILIER:
			default:
				return 's\'est <b>affili&eacute;(e)</b> &agrave;:';
		}
	}
	public function printPrivate(&$bdd){
		return '';
	}

	public function getBackground(){
		switch(parent::getEvent()->getState()){
			case affilia::AFFI_AFFILIER:
				return '#DDDDDD';
			default:
				return '#CCCCCC';
		}
	}
}