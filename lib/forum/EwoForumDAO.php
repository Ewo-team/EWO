<?php

require_once ($root_url."/conf/ConnecteurDAO.php");

class EwoForumDAO extends ConnecteurDAO {
    
    public function selectPerso($pseudo) {
        $sql = 'SELECT user_id FROM phpbb_users WHERE username_clean = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($pseudo));
        return $this->fetch();          
    }
    
    public function selectLegions($name) {
        $sql = "SELECT group_id FROM phpbb_groups WHERE group_name LIKE = ' $name%'";
        $this->query($sql);
        return $this->fetchAll();             
    }
    
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
        $sql = 'UPDATE phpbb_users SET user_password = :hash WHERE username_clean = :pseudo';
        $query = $this->prepare($sql);
        foreach ($pseudo as $p) {
            $this->executePreparedStatement($query,array(':hash' => $hash, ':pseudo' => $p));        
        }
    }
    
    public function isBlankPassword($pseudo) {
        $sql = 'SELECT user_password as pass FROM phpbb_users WHERE username_clean = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($pseudo));
        $result = $this->fetch();   
        return (isset($result['pass']) && $result['pass'] == 'blanc') ? true : false;
    }
    
    public function addPerso($us, $us_clean, $pwd, $email, $email_hash) {
        $sql = "INSERT INTO phpbb_users (`username`, `username_clean`, `user_password`, `user_email`, `user_email_hash`)
                VALUES (:user, :userclean, :pass, :mail, :hash )";
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(
            ':user' => $us,
            ':userclean' => $us_clean,
            ':pass' => $pwd,
            ':mail' => $email,
            ':hash' => $email_hash
        )); 
        
    }
    
    public function removeGroup($id,$group) {
                
        $sql = "DELETE FROM phpbb_user_group WHERE user_id = :id AND group_id IN (:group)";
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(
                    ':id' => id,
                    ':group' => $groupes
                ));        
    }
    
    public function addGroup($id,$group) {
        $sql = "INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (:group, :id, 0, 0)"; 
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(':id' => $id, ':group' => $group));   
    }
}