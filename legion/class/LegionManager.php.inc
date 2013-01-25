<?php
/**
 * Class pour la gestion de toutes les légions
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
 
namespace legions;

require_once($root_url.'legion/class/ManagerFactory.php.inc');
require_once($root_url.'legion/class/Legion.php.inc');
require_once($root_url.'legion/class/LegionConfig.php.inc');
require_once($root_url.'legion/class/LegionDAO.php.inc');

	class LegionManager{
	
		private $legions 	= array();
		private $alliances	= array();
	
		public function __construct($race){
			//Construction à partir de la base de donnée
			$sql 	= LegionDAO::getInstance();
			$query	= '
				SELECT
					id 				as id,
					nom				as nom,
					race			as race,
					description 	as descr,
					type			as type,
					alignement		as alignement,
					creation_date	as creationDate,
					link1			as link1,
					link2			as link2,
					link3			as link3,
					link4			as link4,
					link5			as link5,
					logo_url		as logoUrl
				FROM factions
				WHERE nature = "LEGION" ';
				
			switch($race){
				case LegionConfig::anges:
				case LegionConfig::demons:
				case LegionConfig::humains:
					$id = array_keys(LegionConfig::$bddId,$race);
					$query .= '
					AND race = '.$id[0];
					break;
			}
			
			$query .= '
			ORDER BY nom';
			
			$stmt = $sql->query($query);
			while($legion = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
				$this->legions[] = new Legion($legion);
			}
		}
	
		/**
		 * getLegions
		 * 
		 * Si pas de pramètre (ou nul) : retourne le tableau
		 * Sinon un id : on renvoit la fonction (si elle existe), sinon exeception
		 */
		public function getLegions($id = null){
			if($id == null)
				return $this->legions;
			foreach($this->legions as $legion)
				if($legion->getId() == $id)
					return $legion;
			throw new \Exception('La faction d\'id : '.$id.' n\'existe pas');
		}
	
                /**
                 * getLegion
                 * 
                 * @param int $id l'id de la légion en question
                 * @return retourne false en cas de problème  
                 */
                public static function getLegion($id){
                    $sql 	= LegionDAO::getInstance();
                    $query	= '
                    SELECT
                            id 				as id,
                            nom				as nom,
                            race			as race,
                            description 	as descr,
                            type			as type,
                            alignement		as alignement,
                            creation_date	as creationDate,
                            site_url		as siteUrl,
                            logo_url		as logoUrl
                    FROM factions
                    WHERE id = '.$id.';';
                    $stmt = $sql->query($query);
           
                    if($legion = $sql->fetch($stmt,\PDO::FETCH_OBJ))
                        return new Legion($legion);
                    else
                        return false;
                }
        }
?>
