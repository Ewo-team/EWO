<?php
/**
 * Classe abstraite qui permet de gérer les demandes. Deux instanciations, une pour les persos, l'autre pour les légions
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
 

namespace legions; 
 
require_once($root_url.'legion/class/DemandeManager.php.inc');
require_once($root_url.'legion/class/LegionDAO.php.inc');

	class DemandeFromUtilisateur extends DemandeManager{
		
		protected function loadDemandes($id){
			//On charge les demandes des persos qui viennent de cet utilisateur
			$sql = LegionDAO::getInstance();
			$query = '
			SELECT
				wf.perso_id     as persoId,
                                p.nom           as persoNom,
				wf.faction_id	as factionId,
                                f.nom           as factionNom
			FROM `wait_faction` wf
			INNER JOIN `persos` p
			ON
                            p.id    = wf.perso_id
                        INNER JOIN `factions` f
                        ON
                            f.id    = wf.faction_id
			WHERE
				wf.demandeur    = 1 AND
                                wf.faction_id   = '.$id.';
			';
                        
			$stmt = $sql->query($query);
			while($entree = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
				$this->demandes[] = new Demande($entree->persoId, $entree->persoNom,
                                        $entree->factionId, $entree->factionNom,1);
			}
		}
		
		public function addDemande($perso,$legion){
			$sql = LegionDAO::getInstance();
                        $persoNom   = $sql->getPersoName($perso);
                        $legionNom  = $sql->getLegionName($legion);
			$query = '
			INSERT INTO `wait_faction` (`perso_id`,`faction_id`,`demandeur`)
			VALUES('.$perso.','.$legion.',1);';
			$sql->query($query);
			$this->demandes[] = new Demande($perso,$persoNom, $legion, $legionNom,1);
		}
	}
?>
