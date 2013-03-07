<?php

namespace persos\creation;

use \conf\ConnecteurDAO as ConnecteurDAO;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InscriptionDAO
 *
 * @author Leo
 */

class CreationPersoDAO extends ConnecteurDAO {

    /**
     * Retourne true si un perso avec le nom $name existe
     * @param string $name
     * @return boolean
     */
    public function VerifyExistName($name) {
        $sql = 'SELECT count(nom) FROM persos WHERE nom = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($name));
        $result = $this->fetch_row();
        return ($result[0] == 0) ? false : true;           
    }
    
    public function SelectRaceId($race, $gameplay) {
        $sql = "SELECT races.race_id, races.camp_id
        FROM races, camps
        WHERE camps.id = races.camp_id AND
        races.grade_id = -2 AND 
        camps.nom = :race AND races.type = :gp"; 

		/*echo "SELECT races.race_id, races.camp_id
        FROM races, camps
        WHERE camps.id = races.camp_id AND
        races.grade_id = -2 AND 
        camps.nom = $race AND races.type = $gameplay";*/
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(':race' => $race, ':gp' => $gameplay));
        return $this->fetch();    
    }
    
    public function InsertPerso($perso) {
        
		$query = "INSERT INTO persos (
                                `id`, `background`, `description_affil`, `classe`, `utilisateur_id`, `nb_suicide`, `race_id`,
                                `superieur_id`, `grade_id`, `faction_id`, `nom`, `creation_date`, `date_tour`, `date_esquivemagique`,
                                `avatar_url`, `icone_id`, `galon_id`, `options`, `mdj`, `signature`, `sexe`)
                        VALUES (
                                ".$perso->Mat.", '', '', :classe, :utilisateur, 0, :race,
                                null, :grade, 0, :nom, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(),
                                '', 0, 1, '0', '', '', :sexe)";
								
        $this->prepare($query);
        $this->executePreparedStatement(null, array(
			':classe' => $perso->Classe, 
			':utilisateur' => $perso->UtilisateurId,
			':race' => $perso->RaceId,
			':grade' => $perso->Grade,
			':nom' => $perso->Nom,
			':sexe' => $perso->Sexe
			));								
		/*echo $query;
		exit;*/

        //$this->exec($query);

        return $this->_conn->lastInsertId();        
    }
    
    public function InsertCarac($perso) {
 
		$query = "INSERT INTO `caracs` (
                                `perso_id`, `px`, `pi`, `pv`, `recup_pv`, 
                                `niv`, `mouv`, `pa`,
                                `des_attaque`, `force`, `perception`,`res_mag`)
                        VALUES (
                                '".$perso->Mat."', '".$perso->Xp."', '".$perso->Xp."', '".$perso->Pv."','".$perso->RecupPv."',
                                '".$perso->Niveau."', '".$perso->Mouvement."', '".$perso->Pa."',
                                '".$perso->Des."', '".$perso->Force."', '".$perso->Perception."', '".$perso->ResistanceMagique."')";
 
		//echo $query . '<br>';

        $this->exec($query);        
    }
    
    public function InsertCaracAlter($mat) {
    
			$query = "INSERT INTO `caracs_alter` (`perso_id`) VALUES ('$mat')";

	
            $this->exec($query);
    }
    
}

?>
