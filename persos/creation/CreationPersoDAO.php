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

        $this->prepare($sql);
        $this->executePreparedStatement(null, array(':race' => $race, ':gp' => $gameplay));
        return $this->fetch();    
    }
    
    public function InsertPerso($perso) {
        

        $this->exec("INSERT INTO persos (
                                `id`, `background`, `description_affil`, `utilisateur_id`, `nb_suicide`, `race_id`,
                                `superieur_id`, `grade_id`, `faction_id`, `nom`, `creation_date`, `date_tour`,
                                `avatar_url`, `icone_id`, `galon_id`, `options`, `mdj`, `signature`, `sexe`)
                        VALUES (
                                $perso->Mat, '', '', $perso->UtilisateurId, '', $perso->RaceId,
                                null, $perso->Grade, '', '$perso->Nom', CURRENT_TIMESTAMP(), '',
                                '', '', '', '0', '', '', '".$perso->Sexe."')");

        return $this->_conn->lastInsertId();        
    }
    
    public function InsertCarac($perso) {
 
        $this->exec("INSERT INTO `caracs` (
                                `perso_id`, `px`, `pi`, `pv`, `recup_pv`, `malus_def`,
                                `niv`, `cercle`, `mouv`, `pa`, `pa_dec`,
                                `des_attaque`, `maj_des`, `force`, `perception`,`res_mag`)
                        VALUES (
                                '$perso->Mat', '$perso->Xp', '$perso->Xp', '$perso->Pv','$perso->RecupPv', '0',
                                '$perso->Niveau', '', '$perso->Mouvement', '$perso->Pa', '',
                                '$perso->Des', '', '$perso->Force', '$perso->Perception', '$perso->ResistanceMagique')");        
    }
    
    public function InsertCaracAlter($mat) {
    
            $this->exec("INSERT INTO `caracs_alter` (
                                `perso_id`, `alter_pa`, `alter_mouv`, `alter_def`, `alter_att`,
                                `alter_recup_pv`, `alter_force`, `alter_perception`, `nb_desaffil`, `alter_niv_mag`)
                        VALUES (
                                '$mat', '', '', '', '',
                                '', '', '', '', '')");
    }
    
}

?>
