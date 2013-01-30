<?php

namespace inscription;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InscriptionDAO
 *
 * @author Leo
 */
require_once ( SERVER_ROOT .  "/conf/ConnecteurDAO.php");

class InscriptionDAO extends \ConnecteurDAO {

    /**
     * Retourne true si un utilisateur avec le nom $name existe
     * @param string $name
     * @return boolean
     */
    public function VerifyExistName($name) {
        $sql = 'SELECT count(nom) FROM utilisateurs WHERE nom = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($name));
        $result = $this->fetch_row();
        return ($result[0] == 0) ? false : true;           
    }
    
    /**
     * Retourne true si un utilisateur avec l'email $email existe
     * @param string $email
     * @return boolean
     */
    public function VerifyExistEmail($email) {
        $sql = 'SELECT count(nom) FROM utilisateurs WHERE email = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($email));
        $result = $this->fetch_row();
        return ($result[0] == 0) ? false : true;                
    }
    
    /**
     * Retourne true si un utilisateur avec le ticket $ticket existe
     * @param string $ticket
     * @return boolean
     */
    public function VerifyExistTicket($ticket) {
        // She got a ticket to ride
        $sql = 'SELECT count(nom) FROM utilisateurs WHERE ticket = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($ticket));
        $result = $this->fetch_row();
        return ($result[0] == 0) ? false : true;           
    }   
    
    /**
     * Supprime le ticket
     * @param string $ticket
     */
    public function RemoveTicket($ticket) {
        $sql = "DELETE FROM invitations WHERE numero=?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($ticket));        
    }
    
    /**
     * Sélectionne l'utilisateur ayant le code $code
     * @param string $code
     */
    public function SelectUserByCode($code) {
        $sql = "SELECT * FROM `utilisateurs` WHERE codevalidation = ?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($code));  
        return $this->fetch();
    }
    
    /**
     * Sélectionne l'utilisateur ayant l'email $email
     * @param string $email
     */
    public function SelectUserByEmail($email) {
        $sql = "SELECT * FROM `utilisateurs` WHERE email = ?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($email));  
        return $this->fetch();        
    }
        
    /**
     * Ajoute un nouvel utilisateur
     * @param string $nom
     * @param string $mail
     * @param string $hash
     * @param string $code
     * @param string $session
     */
    public function AddUser($nom,$mail,$hash,$code,$session) {
        $sql = "INSERT INTO utilisateurs(nom, email, passwd,  
            date_enregistrement, droits, options, codevalidation, session_id, 
            bals_speed, template, redirection) VALUES(:nom,:mail,:pass,NOW(),'0000','',:code, :session, '0.5','defaut', '1')";
        $this->prepare($sql);
        $result = $this->executePreparedStatement(null, array(
            ":nom" => $nom, 
            ":mail" => $mail, 
            ":pass" => $hash, 
            ":code" => $code,             
            ":session" => $session
         ));    
        
        return $result;
    }
    
    /**
     * Active le compte pour l'utilisateur ayant le code $code
     * @param string $code
     */
    public function ActiveCompte($code) {
        $sql = "UPDATE utilisateurs SET droits=1000 WHERE codevalidation = ?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($code));           
    }
        
    
}

?>
