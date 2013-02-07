<?php

namespace persos\annuaire;
use \persos\PersosDAO as PersosDAO;

/**
 * Connecteur DAO pour l'annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 * @category dao
 */


class AnnuaireDAO extends PersosDAO {
				
	public function AddPersoToRepertoire($perso, $contact) {
		if($this->persoExist($contact)){
			$sql = "INSERT IGNORE INTO repertoire (id, perso_id, contact_id) VALUES ('', '$perso', '$contact')";
			return $this->exec($sql);
		}
	}
	

}
