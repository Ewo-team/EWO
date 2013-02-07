<?php

namespace jeu\decors;

class Decors {

	private $taillecarte;
	private $carte;
	private $chargement;

	private function __construct($carte) {
	
		// V�rifie si la carte existe. ATTENTION, pas de traitement de v�rification:
		include('../images/cartes/maps/'.$carte.'.php');
		
		$this->taillecarte = $carte_taille;
		$this->carte = $carte;
		$this->chargement = array();
		
		//echo "Cr�ation d'une map $carte d'offset $carte_taille";
	}
	
	public function getCase($x, $y) {
	
		static $carte;
		static $chargement = array();
		//verifie si la map � �t� charg�e
		$nom = $this->carte.'_'.ceil($x/$this->taillecarte).'_'.ceil($y/$this->taillecarte);
		//print_r($chargement);
		if(!in_array('MAP_'.strtoupper($nom),$chargement)) {
		//if(!defined('MAP_'.strtoupper($nom))) {
			@include('../images/cartes/maps/'.$nom.'.php');
			//echo "<br>inclusion de 'maps/$nom.php'";
		}
		if(isset($carte[$x][$y])) {
			return $carte[$x][$y];	
		} else {
			return null;
		}
	}
	
	public static function prepareDecors($carte) {
		//echo " - preparation de la carte $carte";
		if(file_exists('../images/cartes/maps/'.$carte.'.php')) {
			//echo " - elle existe!";
			return new Decors($carte);			
		}
		//echo " - on renvoie la carte";
		return null;
	}

}

?>