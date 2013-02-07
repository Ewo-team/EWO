<?php
/**
 * Class pour la gestion des demandes (dans les deux sens)
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
namespace legions;
	class Demande{
		private $persoId;
                private $persoNom;
		private $legionId;
                private $legionNom;
		private $demandeur;
		
		private $modif = array();
		
		public function __construct($persoId, $persoNom, $legionId, $legionNom, $demandeur){
			$this->persoId		= $persoId;
                        $this->persoNom         = $persoNom;
			$this->legionId		= $legionId;
                        $this->legionNom        = $legionNom;
			$this->demandeur	= $demandeur;
		}
		
		/**
		 * Getters
		 */
		 public function getPersoId(){
			 return $this->persoId;
		 }
                 
                 public function getPersoNom(){
			 return $this->persoNom;
		 }
		 
		 public function getLegionId(){
			 return $this->legionId;
		 }
                 
                 public function getLegionNom(){
			 return $this->legionNom;
		 }
		 
		 public function getDemandeur(){
			 return $this->demandeur;
		 }
	}
?>
