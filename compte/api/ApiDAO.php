<?php
/**
 * Connecteur DAO pour l'api
 * 
 * @author Ganesh
 * @version 1
 * @package api
 * @category dao
 */
use \conf\ConnecteurDAO as ConnecteurDAO;

class ApiDAO extends ConnecteurDAO {
				

	public function SelectKey($k) {
		$sql = "SELECT * FROM api_key WHERE cle=?";	
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($k));			
		return $this->fetch();			
	}

	public function SelectPersos($id) {
		$sql = "SELECT nom, date_tour, carte_id FROM persos LEFT JOIN damier_persos ON (persos.id = damier_persos.perso_id) WHERE utilisateur_id = ? ";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));			
		return $this->fetchAll();			
	}
	

}
