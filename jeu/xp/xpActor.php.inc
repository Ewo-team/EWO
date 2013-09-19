<?php

namespace jeu\xp;


/**
 * @abstract C'est l'objet pour lequel on va calculer l'xp
 * @author Kamule
 *
 * Ceci est un conteneur simple qui va gérer les inputs du calcul d'xp
 */
class XpActor {
	//Liste des camps
	const HUMAIN	= 1;
	const PARIA 	= 2;
	const ANGE		= 3;
	const DEMAIN 	= 4;
	
	const T1 = 1;
	const T4 = 4;
	
	
	protected $mat;
	protected $rangXp;
	protected $pa;
	protected $race;
	protected $rationPa;
	protected $type;
	
	public function __construct($mat, $rangXp, $pa, $race, $type){
		$this->mat		= $mat;
		$this->rangXp	= $rangXp;
		$this->race		= $race;
		
		$this->setPa($pa);
	}
	
	protected function setPa($pa){
		$this->pa = $pa;
		if($this->type == 4)
			$this->rationPa = $this->pa/1.5;
		else
			$this->rationPa = $this->pa/2;
	}
	
	public function getCamps(){
		return $this->camp;
	}
}