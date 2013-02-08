<?php
/**
 * Classe abstraite qui permet de gérer les demandes. Deux instanciations, une pour les persos, l'autre pour les légions
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
 

namespace legions; 


require_once($root_url.'jeu/legion/class/Demande.php.inc');
require_once($root_url.'jeu/legion/class/LegionDAO.php.inc');

	abstract class DemandeManager{
		protected $demandes = array();
		
		public function __construct($id){
			$this->loadDemandes($id);
		}
		
		protected abstract function loadDemandes($id);
		
		public function getDemandes(){
			return $this->demandes;
		}
		
		/**
		 * addDemande
		 * Créé une nouvelle demande
		 * 
		 * /!\ Ne fait aucune vérification d'unicité, c'est à faire avant l'appel
		 */
		public abstract function addDemande($perso,$legion);
		
		public function supDemande($demande){
			foreach($this->demandes as $d)
				if($d == $demande){
					$sql = LegionDAO::getInstance();
					$query = '
					DELETE FROM
						`wait_faction`
					WHERE
						perso_id 	= '.$demande->getPersoId().' AND
						faction_id	= '.$demande->getLegionId().' AND
						demandeur	= '.$demande->getDemandeur().'
					';
					$sql->exec($query);
				}
		}
	}
?>
