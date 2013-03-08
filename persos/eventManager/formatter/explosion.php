<?php
include_once('formatter.php');
class explosion extends formatter{
	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			case 1:
				return 'a <b>tu&eacute;</b>';
			case 0:
				return 'a <b>bless&eacute;</b>';
		}
	}
	public function printPrivate(&$bdd){

		return '';
	}

	public function getBackground(){
		switch(parent::getEvent()->getState()){
			case 1:
				return '#FFCCCC';
			case 0:
				return '#DDDDDD';;
		}
	}
}
