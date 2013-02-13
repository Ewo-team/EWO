<?php

/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@esial.com>
 */
include_once('formatter.php');

class legion extends formatter{
	public function printPublic(&$bdd){
		switch(parent::getEvent()->getState()){
			// quitter
			case 2:
				return 'a quitté la l&eacute;gion : ';
				// se faire virer
			case 1:
				return 's\'est fait virer de la l&eacute;gion :';
				// revoindre
			case 0:
			default:
				return '&agrave; rejoint la l&eacute;gion : ';
		}
	}
	public function printPrivate(&$bdd){
		return '';
	}

	public function getBackground(){
		switch(parent::getEvent()->getState()){
			case 1:
				return '#DDDDDD';
			case 0:
			default:
				return '#CCCCCC';
		}
	}
}
