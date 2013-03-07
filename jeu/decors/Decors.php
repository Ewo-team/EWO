<?php

namespace jeu\decors;

class Decors {

	private $taillecarte;
	private $carte;
	private $chargement;

	private function __construct($carte) {
	
		// Vérifie si la carte existe. ATTENTION, pas de traitement de vérification:
		include(SERVER_ROOT . '/jeu/decors/maps/'.$carte.'/'.$carte.'.php');
		
		$this->taillecarte = $carte_taille;
		$this->carte = $carte;
		$this->chargement = array();
		
		//echo "Création d'une map $carte d'offset $carte_taille";
	}
	
	// retourne les infos d'une case précise
	public function getCase($x, $y) {
	
		$carte = $this->getCases($x,$y);
		
		if(isset($carte[$x][$y])) {
			return $carte[$x][$y];	
		} else {
			return null;
		}
	}
	
	// retourne toutes les infos d'un fragment correspondant à une case
	public function getCases($x,$y) {
		static $carte = array();
		static $chargement = array();
		
		$taillecarte = $this->taillecarte;
		
		$x = ceil($x/$this->taillecarte);
		$y = ceil($y/$this->taillecarte);
		
		//$x = ($x == -0) ? 0 : $x;
		//$y = ($y == -0) ? 0 : $y;
	
		
		//verifie si la map à été chargée
		$nom = $this->carte.'_'.$x.'_'.$y;
		//print_r($chargement);
		if(!in_array('MAP_'.strtoupper($nom),$chargement)) {
		//if(!defined('MAP_'.strtoupper($nom))) {
			include(SERVER_ROOT . '/jeu/decors/maps/'.$this->carte.'/'.$nom.'.php');
			//echo "<br>inclusion de 'maps/$nom.php'";
		}

		return $carte;
	}
	
	public static function prepareDecors($carte) {
		//echo " - preparation de la carte $carte";
		if(file_exists(SERVER_ROOT . '/jeu/decors/maps/'.$carte.'/'.$carte.'.php')) {
			//echo " - elle existe!";
			return new Decors($carte);			
		}
		//echo " - on renvoie la carte";
		return null;
	}

}

?>