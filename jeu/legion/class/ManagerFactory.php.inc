<?php

namespace jeu\legion;

/**
 * Classe dont le but est de charger les manager et de les fournir si besoin
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
 

	class ManagerFactory{
	
		public function __construct(){
			
		}
		
		/**
		 * get
		 * 
		 * Obtenir un manager en fonction de la race donnée
		 */
		public function get($race){
			switch($race){
				case LegionConfig::all:
				case LegionConfig::anges:
				case LegionConfig::demons:
				case LegionConfig::humains:
					return $this->load($race);
				default:
					throw new \Exception('ManagerFactory de legion : Race inconnue ('.$race.')');
			}
		}
		
		
		/**
		 * load
		 * 
		 * Charge depuis APC, si n'existe pas dans APC va créer la classe
		 */
		private function load($race){
			$entree = LegionConfig::$apcId[$race];
			
			/*
			if(\conf\VariableStorage::Exists($entree))
			
				return \conf\VariableStorage::Fetch($entree);//*/
			
			
			$r = new LegionManager($race);
			\conf\VariableStorage::Store($entree, $r);
			return $r;
		}
	}

?>
