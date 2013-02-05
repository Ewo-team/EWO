<?php

namespace compte;
use \conf\ConnecteurDAO as ConnecteurDAO;

/**
 * Connecteur DAO pour le compte
 * 
 * @author Ganesh
 * @version 1
 * @package compte
 * @category dao
 */

class CompteDAO extends ConnecteurDAO {

    public function SelectKeys($id) {
        $sql = "SELECT * FROM api_key WHERE utilisateur_id = ?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($id));
        return $this->fetchAll();
    }

    public function InsertKey($id, $niveau, $nom) {
        $sql = "INSERT INTO api_key (utilisateur_id, nom, cle, niveau)
		VALUES (:user, :nom, MD5(NOW() + :user), :niveau)";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(":user" => $id, ":niveau" => $niveau, ":nom" => $nom));
    }

    public function DeleteKey($id, $cle) {
        $sql = "DELETE FROM api_key WHERE utilisateur_id = :user AND cle = :key";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(":user" => $id, ":key" => $cle));
    }

    public function RenewKey($id, $cle) {
        $sql = "UPDATE api_key SET cle = MD5(NOW() + :user) WHERE utilisateur_id = :user AND cle = :key";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(":user" => $id, ":key" => $cle));
    }

    public function SelectUser($id) {
        $sql = 'SELECT * FROM `utilisateurs` WHERE id = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($id));
        $fetch = $this->fetchAll_assoc();
        if (isset($fetch[0])) {
            return $fetch[0];
        }
        return null;
    }
	
	public function SelectUserIdByMat($mat) {
		$sql = 'SELECT utilisateur_id FROM persos WHERE id = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($mat));
        return $this->fetch_array();   		
	}
    
    public function SelectUserVacancies($id) {
        $sql = 'SELECT * FROM utilisateurs_vacances WHERE utilisateur_id = ?'; 
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($id));
        return $this->fetchAll_assoc();        
    }

    public function UpdateGoVacancies($id) {
        $sql = 'INSERT INTO utilisateurs_vacances (utilisateur_id,date_demande) VALUES (:id, NOW())';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(":id" => $id));        
    }    
    
    public function UpdateBackVacancies($id, $date) {
        $sql = 'UPDATE utilisateurs_vacances SET date_retour = :dateret WHERE utilisateur_id = :id';
        $this->prepare($sql);
        $this->executePreparedStatement(null, array(":dateret" => $date, ":id" => $id));        
    }
    
    public function SaveUser($id, $param) {

        if (count($param) > 0) {

            $tab = array();
            $columns = array();

            foreach ($param as $key => $value) {
                $columns[] = '`'.$key.'` = :'.$key;
                $tab[':' . $key] = $value;
            }

            $tab[':uid'] = $id;

            $arr = implode(",", $columns);
            $sql = "UPDATE utilisateurs SET " . $arr . ' WHERE id=:uid';

            $this->prepare($sql);
            $this->executePreparedStatement(null, $tab);
        }	            
    }
    
    public function checkEmail($email) {
        $sql = "SELECT id FROM utilisateurs WHERE email = ?";
        $this->prepare($sql);
        $this->executePreparedStatement(null, array($email));
        return $this->fetchAll();        
    }

}
