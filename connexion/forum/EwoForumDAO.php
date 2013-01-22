<?php

require_once ($root_url."/conf/ConnecteurDAO.php");

class EwoForumDAO extends ConnecteurDAO {
    
    public function listePersos($user_id) {
        $sql = 'SELECT nom FROM persos WHERE utilisateur_id = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($user_id));
        $result = $this->fetchAll();
        $liste = array();
        
        foreach($result as $ligne) {
            $liste[] = $ligne['nom'];
        }
        
        return $liste;
    }
    
    public function setHash(array $pseudo,$hash) {
        $sql = 'UPDATE phpbb_users SET user_password = :hash WHERE username = :pseudo';
        $query = $this->prepare($sql);
        foreach ($pseudo as $p) {
            $this->executePreparedStatement($query,array(':hash' => $hash, ':pseudo' => $p));        
        }
    }
    
    public function isBlankPassword($pseudo) {
        $sql = 'SELECT user_password as pass FROM phpbb_users WHERE username = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($pseudo));
        $result = $this->fetch();   
        return (isset($result['pass']) && $result['pass'] == 'blanc') ? true : false;
    }
}