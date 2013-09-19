<?php

namespace jeu\xp;


/**
 * @abstract classe qui décrit une action pour le calcul d'xp
 * @author Kamule
 */
class XpAction {
	public $cout;
	public $mort; /* bool */
	public $esquive; /*bool*/
	public $plan;
	
	public function __construct($cout, $mort, $esquive, $plan){
		$this->cout 	= $cout;
		$this->mort		= $mort;
		$this->esquive	= $esquive;
		$this->plan		= $plan;
	}
}