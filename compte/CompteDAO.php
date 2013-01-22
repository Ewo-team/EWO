<?php
/**
 * Connecteur DAO pour le compte
 * 
 * @author Ganesh
 * @version 1
 * @package compte
 * @category dao
 */
require_once ("../conf/ConnecteurDAO.php");

class CompteDAO extends ConnecteurDAO {
				

	public function SelectKeys($id) {
		$sql = "SELECT * FROM api_key WHERE utilisateur_id = ?";	
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));			
		return $this->fetchAll();			
	}

	public function InsertKey($id,$niveau,$nom){
		$sql = "INSERT INTO api_key (utilisateur_id, nom, cle, niveau)
		VALUES (:user, :nom, MD5(NOW() + :user), :niveau)";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array(":user" => $id, ":niveau" => $niveau, ":nom" => $nom));		
	}
	
	public function DeleteKey($id,$cle){
		$sql = "DELETE FROM api_key WHERE utilisateur_id = :user AND cle = :key";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array(":user" => $id, ":key" => $cle));					
	}
	
	public function RenewKey($id,$cle){
		$sql = "UPDATE api_key SET cle = MD5(NOW() + :user) WHERE utilisateur_id = :user AND cle = :key";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array(":user" => $id, ":key" => $cle));	
	}

}
