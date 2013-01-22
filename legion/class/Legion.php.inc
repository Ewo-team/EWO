<?php
/**
 * Class pour la gestion d'une faction
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */
namespace legions;

require_once($root_url.'legion/class/LegionDAO.php.inc');
require_once($root_url.'legion/class/Grade.php.inc');

	class Legion{

		private $id;
		private $nom;
		private $descr;
		private $race;
		private $type;
		private $alignement;
		private $creationDate;
		private $siteUrl;
		private $logoUrl;

		public function __construct($id, $nom = null, $descr = null, $race = null, $type = null,
			$alignement = null, $creationDate = null, $siteUrl = null, $logoUrl = null){
			if(is_object($id)) //Si on lui passe un objet qui vient d'un retour sql
				$this->constructObj($id);
			else //Les valeurs sont uniques
				$this->constructAll($id, $nom, $descr, $race, $type, $alignement, $creationDate, $siteUrl, $logoUrl);
		}

			private function constructAll($id, $nom, $descr, $race, $type, $alignement, $creationDate, $siteUrl, $logoUrl){
				$this->setId($id);
				$this->setNom($nom);
				$this->setDescr($descr);
				$this->setRace($race);
				$this->setType($type);
				$this->setAlignement($alignement);
				$this->setCreationDate($creationDate);
				$this->setSiteUrl($siteUrl);
				$this->setLogoUrl($logoUrl);
			}

			private function constructObj($obj){
				$this->setId($obj->id);
				$this->setNom($obj->nom);
				$this->setDescr($obj->descr);
				$this->setRace($obj->race);
				$this->setType($obj->type);
				$this->setAlignement($obj->alignement);
				$this->setCreationDate($obj->creationDate);
				$this->setSiteUrl($obj->siteUrl);
				$this->setLogoUrl($obj->logoUrl);
			}


		/**
		 * Fonctions pour obtenir des infos étendues
		 */

		public function getListMembres(){
			$r = array();

                        $grades = $this->getListGrades();
                        $dernierGrade = end($grades);
                        $dernierGrade = $dernierGrade->getGrade_id();
                        reset($grades);
			$sql 	= LegionDAO::getInstance();
			$query	= '
				SELECT
                                    p.id                    as id,
                                    p.nom                   as nom,
                                    fm.faction_grade_id     as grade_id,
                                    fg.droits               as droits
				FROM persos p
				INNER JOIN faction_membres fm
                                    ON
                                        fm.perso_id 	= p.id AND
                                        fm.faction_id	= '.$this->id.'
                                LEFT JOIN `faction_grades` fg
                                    ON
                                        fg.faction_id   = fm.faction_id AND
                                        fg.grade_id     = fm.faction_grade_id
				ORDER BY p.nom ASC ';

			$stmt = $sql->query($query);
			while($result = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
				$n = array();
				$n['id'] 	= $result->id;
				$n['nom']	= $result->nom;
				$n['grade_id']	= $result->grade_id;
                                    if($n['grade_id'] == null)
                                        $n['grade_id'] = $dernierGrade;
                                $n['droits']    = $result->droits;
				$r[] = $n;
			}
			return $r;
		}

		/*
		 * getListGrades
		 *
		 * returne un tableau avec la liste des grades
		 */
		public function getListGrades(){
			$r = array();

			$sql 	= LegionDAO::getInstance();
			$query	= '
				SELECT
					fg.id 		as id,
					fg.grade_id	as grade_id,
					fg.nom 		as nom,
					fg.description  as description,
					fg.droits	as droits
				FROM faction_grades fg
				WHERE
					fg.faction_id	= '.$this->id.'
				ORDER BY droits DESC, fg.id ASC ';

			$stmt = $sql->query($query);
			while($result = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
                            $r[$result->grade_id] = new Grade($result->id,$result->grade_id,$result->nom,$result->description,$result->droits);
			}

			return $r;
		}

		/**
		 * Getters/Setters
		 */
		public function getId(){
			return $this->id;
		}
		public function setId($id){
			return $this->id = $id;
		}

		public function getNom(){
			return $this->nom;
		}
		public function setNom($nom){
			return $this->nom = $nom;
		}

		public function getDescr(){
			return $this->descr;
		}
		public function setDescr($descr){
			return $this->descr = $descr;
		}

		public function getRace(){
			return $this->race;
		}
		public function setRace($race){
			return $this->race = $race;
		}

		public function getType(){
			return $this->type;
		}
		public function setType($type){
			return $this->type = $type;
		}

		public function getAlignement(){
			return $this->alignement;
		}
		public function setAlignement($alignement){
			return $this->alignement = $alignement;
		}

		public function getCreationDate(){
			return $this->creationDate;
		}
		public function setCreationDate($creationDate){
			return $this->creationDate = $creationDate;
		}

		public function getSiteUrl(){
			return $this->siteUrl;
		}
		public function setSiteUrl($siteUrl){
			return $this->siteUrl = $siteUrl;
		}

		public function getLogoUrl(){
			return $this->logoUrl;
		}
		public function setLogoUrl($logoUrl){
			return $this->logoUrl = $logoUrl;
		}


                public static function getLegionFromId($id){
                    include_once('LegionConfig.php.inc');
                    include_once('ManagerFactory.php.inc');
                    include_once('LegionDroits.php.inc');

                    $factory = new ManagerFactory();
                    $legions = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);

                    return $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);
                }
	}

?>
