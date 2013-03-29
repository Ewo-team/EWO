<?php

require_once "../AdminDAO.php";
use \admin\AdminDAO as AdminDAO;
class Actions {
	public $id = 'nouveau';
	public $nom = array('');
	public $description = '';
	public $cout = 1; // entier positif
	public $cercle = 0; // 0 a 7
	public $niveau = 0; // 0 a 5
	public $races = array(); // nom_race => true
	public $grade = -2; // 0 a 5 ou -2
	public $galon = 0; // 0 a 4
	public $zone = 0; // si plus grand que 0, zone autour de la cible, si plus petit, zone autour du lanceur. Si 0, c'est le lanceur la cible
	public $cible = 1; // Centré sur la cible ou non
	public $lanceur = 1; // prendre en compte le lanceur (?)
	public $effetsLanceur = array();
	public $effetsCible = array();
	public $type_cible = 'both'; // "allie", "ennemi", "both", "choix", "none"
	public $type_action = 'sort'; // "attaque", "entrainement", "sort", "suicide", "sprint", "reparation". "sort" par défaut!
	
	public function creerActions($tableau) {
		$conn = AdminDAO::getInstance();
		
		$this->description = $tableau['description'];
		$this->cout = $tableau['cout'];
		$this->cercle = $tableau['cercle_id'];
		$this->niveau = $tableau['niv'];		
		$this->nom = $tableau['nom'];
		$this->grade = $tableau['grade'];
		$this->galon = $tableau['galon'];
		$this->zone = $tableau['zone'];
		$this->cible = $tableau['cible'];
		$this->lanceur = $tableau['lanceur'];
		$this->type_cible = $tableau['type_cible'];
		$this->type_action = $tableau['type_action'];
		$this->races = Actions::raceStrToArray($tableau['race']);
		
		$effets = explode(":",$tableau['id_effet']);
		$lanceur = explode(",",$effets[0]);
		$cible = explode(",",$effets[1]);

		$this->effetsLanceur = null;
		$this->effetsCible = null;
		
		foreach($lanceur as $effet) {
			if($effet != 0) {
				$result = $conn->SelectEffetById($effet);
				$this->effetsLanceur[] = new Effet($result['type_effet'],$result['effet']);
			}
		}
		
		foreach($cible as $effet) {
			if($effet != 0) {
				$result = $conn->SelectEffetById($effet);
				$this->effetsCible[] = new Effet($result['type_effet'],$result['effet']);
			}
		}				
	}
	
	public static function selectionActions($id) {
		$conn = AdminDAO::getInstance();

		// Une action simple
		$result = $conn->SelectActionById($id);
		$tableau = $result[0];

		$action = new Actions();
		
		$action->id = $id;	
		
		$tableau['nom'] = explode("|",$tableau['nom']);
			
		$action->creerActions($tableau);
		
		return $action;

	}
	
	public static function raceArrayToStr($array) {
	
		$StrRaces = '';
			
		if(array_key_exists("humain",$array)) {
			$StrRaces .= 1;
		} else {
			$StrRaces .= 0;
		}
		
		if(array_key_exists("paria",$array)) {
			$StrRaces .= 1;
		} else {
			$StrRaces .= 0;
		}

		if(array_key_exists("ange",$array)) {
			$StrRaces .= 1;
		} else {
			$StrRaces .= 0;
		}

		if(array_key_exists("demon",$array)) {
			$StrRaces .= 1;
		} else {
			$StrRaces .= 0;
		}	
		return $StrRaces;
	}
	
	public static function raceStrToArray($str) {
	
		$races = str_split(sprintf("%04s",$str));
		
		$array = array();
		
		if($races[0] == 1) {
			$array['humain'] = true;
		}
		
		if($races[1] == 1) {
			$array['paria'] = true;
		}

		if($races[2] == 1) {
			$array['ange'] = true;
		}

		if($races[3] == 1) {
			$array['demon'] = true;
		}
	
		return $array;
	
	}
	
	public function commit() {
		$conn = AdminDAO::getInstance();
		
		$effets = '';

		if(isset($this->effetsLanceur) && count($this->effetsLanceur) > 0) {
			foreach($this->effetsLanceur as $effet) {
				$id = $effet->id();
				$effets_array[] = $id;
			}
			$effets = implode(",",$effets_array).':';
		} else {
			$effets = '0:';
		}
		
		if(isset($this->effetsCible) && count($this->effetsCible) > 0) {
			foreach($this->effetsCible as $effet) {
				$id = $effet->id();
				$effets_array[] = $id;
			}
			$effets .= implode(",",$effets_array);
		} else {
			$effets .= '0';
		}		
		
		if(is_array($this->nom)) {
			$nom = implode("|",$this->nom);
		} else {
			$nom = $this->nom;
		}
			
		$StrRaces = Actions::raceArrayToStr($this->races);
		
		if(is_numeric($this->id)) {
			// On modifie
			$resultat = $conn->ModifieAction($this->id, 
								$nom, 
								$this->description, 
								$this->cout, 
								$this->cercle, 
								$this->niveau, 
								$StrRaces, 
								$this->grade, 
								$this->galon, 
								$this->zone, 
								$this->cible, 
								$this->lanceur, 
								$effets, 
								$this->type_cible, 
								$this->type_action);
		} else {
			// On ajoute
			$resultat = $conn->AjouterAction($nom, 
								$this->description, 
								$this->cout, 
								$this->cercle, 
								$this->niveau, 
								$StrRaces, 
								$this->grade, 
								$this->galon, 
								$this->zone, 
								$this->cible, 
								$this->lanceur, 
								$effets, 
								$this->type_cible, 
								$this->type_action);
		}
		
		return $resultat;

		
		
	}
}

?>