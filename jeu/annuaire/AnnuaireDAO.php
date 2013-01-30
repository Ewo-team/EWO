<?php
/**
 * Connecteur DAO pour l'annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 * @category dao
 */
use \conf\ConnecteurDAO as ConnecteurDAO;

class AnnuaireDAO extends ConnecteurDAO {
				

	public function SelectPersoById($id) {
		$this->SelectData("persos.id = '$id'");
		return $this->fetch_assoc();
	}
	
	public function SelectPersoByName($name) {
		$this->SelectData("persos.nom LIKE '$name'");
		return $this->fetch_assoc();
	}
	
	public function AddPersoToRepertoire($perso, $contact) {
		if($this->persoExist($contact)){
			$sql = "INSERT IGNORE INTO repertoire (id, perso_id, contact_id) VALUES ('', '$perso', '$contact')";
			return $this->exec($sql);
		}
	}
	
	private function SelectData($whereCondition = null) {
		$sql = "SELECT persos.id AS id_personnage, persos.nom AS nom_perso, races.color AS couleur, races.nom AS nom_race   
		FROM persos 
		INNER JOIN races 
		ON persos.race_id = races.id";
		if($whereCondition) {
			$sql .= ' WHERE '.$whereCondition;
		}

		$this->query($sql);

	}
	

}
