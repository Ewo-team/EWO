<?php
/**
 * Outils d'AT
 * 
 * Une série d'outils utilisés pour gérer l'AT
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package antitriche
 */
	define('LEVEL_1',1);
	define('LEVEL_2',2);
	define('LEVEL_3',3);
	define('LEVEL_4',4);
	define('LEVEL_5',5);
	
	define('AT_ACTION_LOG_AT',0);
	define('AT_ACTION_VIRER',1);
	define('AT_ACTION_AJOUTER',2);
	
	$at_action = array(
		AT_ACTION_LOG_AT 	=> 'Voir les logs de l\'AT',
		AT_ACTION_VIRER 	=> 'Virer un membre',
		AT_ACTION_AJOUTER 	=> 'Ajouter un membre'
	);
	
	class ATMenuItem{
		private $name;
		private	$level;
		private	$acronym;
		
		
		/*
		 * n : string name
		 * l : entier entre 1 et 5
		 * a : null si u = false, une string unique sinon
		 */
		function __construct($n,$l,$a = null){
			$this->name		= $n;
			$this->level	= $l;
			$this->acronym	= $a;
			
		}
		
		function getName(){
			return $this->name;
		}
		
		function getLevel(){
			return $this->level;
		}
		
		function isUsable(){
			return $this->acronym != null;
		}
		
		function getAcronym(){
			return $this->acronym;
		}
	}
	
	
	global $at_items;
	$at_items = array(
		array(new ATMenuItem('Outils automatiques',LEVEL_1)
			,array(
				new ATMenuItem('Interactions g&eacute;ographiques',LEVEL_1,'int_geo'),
				new ATMenuItem('Interactions d\'actions',LEVEL_1,'int_act'),
				new ATMenuItem('Connexion sur m&ecirc;me IP',LEVEL_1,'co_ip'),
				new ATMenuItem('Connexion sur m&ecirc;me machine',LEVEL_1,'co_mach'),
			)
		),
		array(new ATMenuItem('Comparateurs',LEVEL_2)
			,array(
				new ATMenuItem('Comparateur d\'IP',LEVEL_2,'cpr_ip'),
				new ATMenuItem('Comparateur de Cookie',LEVEL_2,'cpr_cookie'),
				new ATMenuItem('Comparateur d\'&eacute;v&eacute;nements',LEVEL_2,'cpr_even'),
			)
		),
		new ATMenuItem('Logs',LEVEL_2,'logs_co'),
		new ATMenuItem('Sanctions',LEVEL_3,'sanc'),
		array(new ATMenuItem('Administration',LEVEL_4)
			,array(
				new ATMenuItem('Gestion des AT',LEVEL_4,'admin'),
				new ATMenuItem('Log des AT',LEVEL_4,'log_at'),
			)
		)
	);
	
	function allowed($acronym,$at){
		global $at_items;
		return allowedRec($acronym,$at,$at_items);
	}
	
	function allowedRec($acronym,$at,$tbl){
		foreach($tbl as $val){
			if(is_array($val))
				$item = $val[0];
			else
				$item = $val;
			
			if($item->getAcronym() == $acronym)
				return $item->getLevel() <= $at->getLevel();
			else if(is_array($val))
				if(allowedRec($acronym,$at,$val[1]))
					return true;
		}
		return false;
	}
?>
