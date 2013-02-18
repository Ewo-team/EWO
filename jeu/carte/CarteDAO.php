<?php

namespace jeu\carte;

/**
 * Connecteur DAO pour l'annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 * @category dao
 */
use \conf\ConnecteurDAO as ConnecteurDAO;

class CarteDAO extends ConnecteurDAO {
	
	public function SelectInfosCarte($id) {
		$sql = "SELECT * 
		FROM  `cartes` 
		WHERE  `id` = ?
		LIMIT 1";

		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));	
		return $this->fetch_assoc();
	}
	
	public function SelectPersosFromDamier($carte, $x_min, $x_max, $y_min, $y_max) {
		$sql = "SELECT * FROM damier_persos, persos, races 
		WHERE damier_persos.carte_id = :carte 
			AND damier_persos.perso_id = persos.id 
			AND races.race_id = persos.race_id AND races.grade_id = -2 
			AND(pos_x BETWEEN :xmin AND :xmax) 
			AND (pos_y BETWEEN :ymin AND :ymax) ORDER BY camp_id";
		
		$param = array(":carte" => $carte,":xmin" => $x_min,":xmax" => $x_max,":ymin" => $y_min,":ymax" => $y_max);
		$this->prepare($sql);
		$this->executePreparedStatement(null,$param);	

		return $this->fetchAll_assoc();
	}	
	
	public function SelectBoucliersFromDamier($carte, $x_min, $x_max, $y_min, $y_max) {
		$sql = "SELECT * FROM damier_bouclier
				WHERE (pos_x BETWEEN :xmin AND :xmax) 
					AND (pos_y BETWEEN :ymin AND :ymax)
					AND carte_id = :carte ";

		$param = array(":carte" => $carte,":xmin" => $x_min,":xmax" => $x_max,":ymin" => $y_min,":ymax" => $y_max);
		$this->prepare($sql);
		$this->executePreparedStatement(null,$param);	
		
		return $this->fetchAll_assoc();
	}
	
	public function SelectPortesFromDamier($carte, $x_min, $x_max, $y_min, $y_max) {
		$sql = "SELECT * FROM damier_porte
				WHERE (pos_x BETWEEN :xmin AND :xmax) 
					AND (pos_y BETWEEN :ymin AND :ymax)
					AND carte_id = :carte ";

		$param = array(":carte" => $carte,":xmin" => $x_min,":xmax" => $x_max,":ymin" => $y_min,":ymax" => $y_max);
		$this->prepare($sql);
		$this->executePreparedStatement(null,$param);	
		
		return $this->fetchAll_assoc();
	}	

}
