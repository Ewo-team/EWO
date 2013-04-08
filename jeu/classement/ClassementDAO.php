<?php

namespace jeu\classement;

/**
 * ClassementDAO
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 * @category dao
 */
use \conf\ConnecteurDAO as ConnecteurDAO;

abstract class ClassementDAO extends ConnecteurDAO {
	
	/**
	Constantes de classe
	*/
	
	// Races impliqués. Les valeurs peuvent se combiner. Valeur binaire
	// exemple, pour avoir un classement des anges et parias seulement, passer le param�tre races(ClassementDAO::ANGE | ClassementDAO::PARIA) (ange ou paria)
	// Pour tout le monde sauf les parias, races(ClassementDAO::TOUS ^ ClassementDAO::PARIA) (tous sauf paria)
	const ANGE = 1;
	const HUMAIN = 2;
	const DEMON = 4;
	const AILE = 5;
	const PARIA = 8;
	const TOUS = 15;
	
	// Ordre du classement. Valeur bool�enne
	const CROISSANT = true;
	const DECROISSANT = false;
	
	// Tri par grades, et �ventuellement par galons. Valeur binaire
	const SANSGRADE = 0;
	const GRADESANSGALON = 1;
	const GRADEGALON = 3;
	
	// Types de classements
	const XP = 1;
	const MORT = 2;
	const MEURTRE = 3;
	const TAILLECV = 4; /* La taille du CV = diff�rence entre morts et meutre */
	const SURVIE = 5; /* Le temps depuis la derni�re mort */
	const FAMILLE = 6; /* Classement des familles, avec valeur moyenne des xp */
	const MOSTWANTED = 7; /* Classement des familles, avec valeur moyenne des xp */
	const AUTOGOAL = 8; /* Classement des familles, avec valeur moyenne des xp */
	const REGICIDE = 9; /* Classement des familles, avec valeur moyenne des xp */
	const SUPERMAN = 10; /* Classement des familles, avec valeur moyenne des xp */
		
	/**
	Propri�t�s
	*/
	
	// Date la plus vieille g�r� par la Time Machine
	//public $first_date = 
	public $first_date;
	
	protected $_ange = 1;
	protected $_humain = 1;
	protected $_demon = 1;
	protected $_paria = 1;
	protected $_nbraces = 4;
	protected $_cptraces = 4;
	
	protected $_grade = 0;
	protected $_galon = 0;
	
	protected $_type;
	protected $_morgue = false;
	
	protected $_page = 0;
	protected $_nbParPage = 50;
	protected $_pageMax = 1;
	
	protected $_where_race = 'races.camp_id=';
	
	//protected $_cache = false;
	protected $_date = false;
	
	protected $_select;
	protected $_join;
	protected $_where;
	protected $_group;
	protected $_order;
	
	protected $_sql;
	
	protected function __construct($base = "ewo") {
		parent::__construct($base);
		//$this->prepareClassement();
		$this->archiveClassement();
		$this->first_date = mktime(0,0,0,3,13,2013);	
	}
	
	protected function archiveClassement() {
	
		$filecache = 'test.cache';
		$refresh = false;
		
		if(!file_exists($filecache))
		{
			$refresh = true;
		} else {
			$time = filectime($filecache);
			$now = time();
			
			if((date("z",$time) != date("z", $now)) || $time < $now) {
				$refresh = true;
			}
		}
		
		if($refresh) {
		
			file_put_contents($filecache, $time);
		
			$sql = "CALL archivage_classements();";
			$this->exec($sql);			
		}
	
	}
	
	public abstract function prepareClassement();
	
	public function prepareClassementRegicide() {
	
		// Most Wanted
		if($this->_type === ClassementDAO::MOSTWANTED) {	
			for($i=1; $i<=4; $i++) {
				$sql[$i] = 'SELECT tueur.id, tueur.nom, count(morgue.id) as meurtre
				FROM persos tueur
				LEFT JOIN morgue on (tueur.id = morgue.id_perso)
				LEFT JOIN races on (morgue.race_victime = races.race_id AND morgue.grade_victime = races.grade_id)
				WHERE races.camp_id = '.$i.' AND
				tueur.race_id != races.race_id
				GROUP BY tueur.id
				ORDER BY count(morgue.id) DESC';
			}
		}
		
		// Auto-goal
		if($this->_type === ClassementDAO::AUTOGOAL) {
			for($i=1; $i<=4; $i++) {
				$sql[$i] = 'SELECT tueur.id, tueur.nom, count(morgue.id) as meurtre
				FROM persos tueur
				LEFT JOIN morgue on (tueur.id = morgue.id_perso)
				LEFT JOIN races on (morgue.race_victime = races.race_id AND morgue.grade_victime = races.grade_id)
				WHERE races.camp_id = '.$i.' AND
				tueur.race_id = races.race_id
				GROUP BY tueur.id
				ORDER BY count(morgue.id) DESC';
			}
		}


	}
	
	public function prepareClassementSuperman() {
	
	}
	
	public function prepareClassementArchive() {
	
	}
			
	/**
	 * M�thode publique charg�e d'appeler les bonnes m�thodes priv�es
	 * On d�finie ici quel classement utilise quel m�thode, via un pr�fix
	 * Si la m�thode pr�fix� n'existe pas, il n'y a pas de tentative d'appel
	 */
	//protected function fonctionInvoker($fonction, $param = null)	{
	//	$prefix = $this->_prefix;
		/*switch($this->_type) {
			case ClassementDAO::XP :
			case ClassementDAO::MEURTRE :
			case ClassementDAO::MORT :
			case ClassementDAO::TAILLECV :
			case ClassementDAO::SURVIE :
			case ClassementDAO::FAMILLE :
				$prefix = 'Vue';
				break;
			case ClassementDAO::MOSTWANTED :
			case ClassementDAO::AUTOGOAL :
			case ClassementDAO::REGICIDE :
				$prefix = 'Regicide';
				break;
			case ClassementDAO::SUPERMAN :
				$prefix = 'Superman';
				break;
		}*/
/*
		$name = $fonction.$prefix;
		
		if(method_exists($this,$name)) {
		
			if(isset($param)) {
				return $this->$name($param);
			}
			
			return $this->$name();
		
		}
		
		return null;
	}
	*/	
	protected function separateur(&$sql, &$separateur) {
		if($separateur == true) {
			$this->_cptraces--;
			$compte = $this->_cptraces;
			
			if($compte>0) {
				$sql .= 'OR ';
				$separateur = false;
			}
		}
	}
	
	public abstract function compteLignes();
	
	public abstract function cherchePositionMat($mat);
	
	
	public function retourneClassement() {
		$this->prepare($this->_sql);
		//echo $this->_sql;
		$this->executePreparedStatement();
		return $this->fetchAll();		
	}
		
	public function races($races) {
		if(is_numeric($races)) {		
			$this->_ange = ($races) & 1;
			$this->_humain = ($races >> 1) & 1;
			$this->_demon = ($races >> 2) & 1;
			$this->_paria = ($races >> 3) & 1;	
			
			$this->_nbraces = $this->_ange + $this->_humain + $this->_demon + $this->_paria;
		}
	}

	public function grade($gradegalon){
		if(is_numeric($gradegalon)) {
			$this->_grade = $gradegalon & 1;
			$this->_galon = ($gradegalon >> 1) & 1;
		}
	}
	
	public function type($type) {
		$this->_morgue = false;
		$this->_type = $type;
		if($type == ClassementDAO::MORT || $type == ClassementDAO::MEURTRE || $type == ClassementDAO::TAILLECV) {
			$this->_morgue = true;
		}
	}
	
	public function nombreParPage($nb) {
		if(is_numeric($nb)) {
			$this->_nbParPage = $nb;
		}
	}
	
	public function page($page = null) {
		if(is_numeric($page)) {	
			
			$this->_page = min($page, $this->_pageMax);
		}
		
		return $this->_page;
	}
	
	public function pagesMax() {
		return $this->_pageMax = round($this->compteLignes() / $this->_nbParPage+0.499);
	}
	
	public function date($date) {
		if($date < mktime(0,0,0) && date("U",$date) && $date >= $this->first_date) {
			return $this->_date = $date;
		}
		return $this->_date = mktime(0,0,0);
	}
}
