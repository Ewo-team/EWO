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
	const DEMON 	= 4;
	
	const LEGION_DEFENSE = 3;
	const LEGION_JUSTICE = 4;
	
	const T1 = 1;
	const T4 = 4;
	
	
	protected $_mat;
	protected $_rangXp;
	protected $_pa;
	protected $_camp;
	protected $_rationPa;
	protected $_type;
	protected $_legionType;
	
	public function __construct($mat, $rangXp, $pa, $camp, $type, $legionType){
		$this->_mat			= $mat;
		$this->_rangXp		= $rangXp;
		$this->_camp		= $camp;
		$this->_legionType	= $legionType;
		
		$this->setPa($pa);
	}
	
	protected function setPa($pa){
		$this->_pa = $pa;
		if($this->_type == 4)
			$this->_rationPa = $this->_pa/1.5;
		else
			$this->_rationPa = $this->_pa/2;
	}
	
	public function getCamps(){
		return $this->_camp;
	}
	
	public function getLegionType(){
		return $this->_legionType;
	}
	
	public function getRang(){
		return $this->_rangXp;
	}
	
	public function getRatioPA(){
		return $this->_rationPa;
	}
}