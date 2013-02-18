<?php

namespace jeu\legion;

/**
 * Messagerie DAO, requete pour construire la messagerie <= NON!
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 * @category dao
 */

use \conf\ConnecteurDAO as ConnecteurDAO;

class LegionDAO extends ConnecteurDAO {

    /**
     * sendInvitation
     *
     * Envoyer une invitation à un perso.
     * Fait les vérifications de camp et d'existance, renvoit une exception avec un message en cas de problème
     */
    public function sendInvitation($legionId, $persoId, $demandeur){
        //Checker la race du perso et de la légion
        $query = '
            SELECT
                r.camp_id   as perso,
                f.race      as legion
            FROM (`persos` p, `factions` f)
            INNER JOIN `races` r
                ON r.race_id = p.race_id
            WHERE
                p.id    = '.$persoId.' AND
                f.id    = '.$legionId.';';

        $stmt = $this->query($query);
        $data = $this->fetch($stmt,\PDO::FETCH_OBJ);
        if(!$data)  //si pas de retour
            throw new \Exception("Le personnage et/ou la légion en question n'exite,t pas.");
        if($data->perso != $data->legion)  //si le camp du perso n'est pas celui de la légion
            throw new \Exception("Ce personnage ne fait pas partie du bon camp");

        //Tout est clean, on envoit le bouzin !

        $query = '
            INSERT INTO  `wait_faction`
            (`perso_id`, `faction_id`, `demandeur`)
            VALUES
            ('.$persoId.','.$legionId.','.$demandeur.');';
        if(!$this->exec($query))//On execute la requête et on check en même temps le retour
            throw new \Exception("Erreur lors de l'insertion.");
    }

    /**
     * sendInvitation
     *
     * Envoyer une invitation à un perso.
     * Fait les vérifications de camp et d'existance, renvoit une exception avec un message en cas de problème
     */
    public function sendVirerNotifn($legionId, $persoId){
        
        $query = '
            INSERT INTO  `wait_faction`
            (`perso_id`, `faction_id`, `demandeur`)
            VALUES
            ('.$persoId.','.$legionId.','.$demandeur.');';
        if(!$this->exec($query))//On execute la requête et on check en même temps le retour
            throw new \Exception("Erreur lors de l'insertion.");
    }

    /**
     * removeInvitiation
     *
     * Supprime une invitation
     *
     * @param type $legionId
     * @param type $persoId
     * @param type $demandeur
     * @throws \Exception
     */
    public function removeInvitation($legionId, $persoId, $demandeur){
        $query = '
            DELETE FROM  `wait_faction`
            WHERE
                `perso_id`      = '.$persoId.'  AND
                `faction_id`    = '.$legionId.' AND
                `demandeur`     = '.$demandeur.'
            ;';
        if(!$this->exec($query))//On execute la requête et on check en même temps le retour
            throw new \Exception("Erreur lors de la suppression.");
    }

    /**
     * removeAllInvitation
     *
     * Supprime toutes les invitations qui trainent
     * @param int $legionId
     * @param int $persoId
     * @throws \Exception
     */
    public function removeAllInvitation($persoId){
        $query = '
            DELETE FROM  `wait_faction`
            WHERE
                `perso_id`      = '.$persoId.'
            ;';
        if(!$this->exec($query))//On execute la requête et on check en même temps le retour
            throw new \Exception("Erreur lors de la suppression.");
    }

    public function addMember($legionId,$persoId){

        //Selection du grade le plus merdique !
        $query = '
            SELECT grade_id as i
            FROM `faction_grades`
            WHERE
            faction_id = '.$legionId.'
            ORDER BY droits ASC
            LIMIT 0,1;';
        $stmt = $this->query($query);
        $data = $this->fetch($stmt,\PDO::FETCH_OBJ);

        $query = '
            INSERT INTO  `faction_membres`
            (`perso_id`,`faction_id`,`faction_grade_id`)
            VALUES('.$persoId.','.$legionId.','.$data->i.');';

        if(!$this->exec($query))//On execute la requête et on check en même temps le retour
            throw new \Exception("Erreur lors de la suppression.");
    }

    public function getPersoName($perso){
         $query = '
            SELECT
                nom
            FROM `persos`
            WHERE
                id    = '.$perso.';';

        $stmt = $this->query($query);
        $data = $this->fetch($stmt,\PDO::FETCH_OBJ);
        return $data->nom;
    }
    public function getLegionName($legion){
        $query = '
            SELECT
                nom
            FROM `factions`
            WHERE
                id    = '.$legion.';';

        $stmt = $this->query($query);
        $data = $this->fetch($stmt,\PDO::FETCH_OBJ);
        return $data->nom;
    }

    public function lastInsertId(){
        return $this->_conn->lastInsertId();
    }

}
