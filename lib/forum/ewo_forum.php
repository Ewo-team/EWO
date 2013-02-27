<?php


if(!defined('IN_PHPBB')) {
    define('IN_PHPBB', true);
}

$phpEx = 'php';
$phpbb_root_path = SERVER_ROOT . '/forum/';

if(isset($include_forum)) {

    include_once(SERVER_ROOT . '/forum/common.php');

}

include 'EwoForumDAO.php';
include 'EwoForumConfig.php';

class EwoForum {
    
    private $id;
    private $jeu;
    private $forum;
    
    public function __construct($user = 1) {
        $this->id = $user;
        $this->jeu = EwoForumDAO::getInstance();
        $this->forum = EwoForumDAO::getInstance("forum");
    }
   
    public function createPerso($pseudo, $mail, $password = null) {
        if($password != null) {
            $pass = phpbb_hash($password);
        } else {
            $hash = $this->selectPassEmail($mail);
            
            if(isset($hash['user_password'])) {
                $pass = $hash['user_password'];
            } else {
                $pass = 'blanc';
            }
            
        }
        
        $email_hash = phpbb_email_hash($mail);
        $clean = utf8_clean_string($pseudo);
        
        $this->forum->addPerso($pseudo, $clean, $pass, $mail, $email_hash);
    }
    
    public function selectPassEmail($email) {
        return $this->forum->getHashEmail($email);
    }
    
    public function lierComptes($email) {
        $this->forum->setMasterId($email);
    }
    
    public function setRaceGrade($id,$race,$grade,$galon) {
        
        if(is_numeric($id)) {
            
        } else {
            $clean = utf8_clean_string(id);
        }
        
        
        
        $id = $this->forum->selectPerso($clean);
        
        $grp_list = array();
        
        global $groupes;
        
        foreach($groupes as $race) {  
            foreach($race as $groupe) {  
                $grp_list = $groupe;
            }           
        }        
        
        $this->forum->removeGroup($id, implode(",", $grp_list));
        
        $this->forum->addGroup($id,$groupes[$race]["Base"]);
        
        if($grade >= 4) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }            
            
            if(isset($groupes[$race]["Chef"])) {
                $this->forum->addGroup($id,$groupes[$race]["Chef"]);
            }
        } else if($grade >= 3 && $galon >= 2) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }
        }        
    }
    
    public function changePasswords($password) {
        $hash = phpbb_hash($password);

        $liste_persos = $this->jeu->listePersos($this->id);

        $this->forum->setHash($liste_persos,$hash);
    }
    
    public function emptyPassword() {
        throw new Exception("Undefined method");
    }
    
    public function isBlank($pseudo) {
        return $this->forum->isBlankPassword($pseudo);
    }
    
    public function createLegion($legionName) {
        //throw new Exception("Undefined method");        
    }
    
    public function removeLegion($legionName) {
        //throw new Exception("Undefined method");       
    }

    public function addMemberLegion($mat, $legionName, $rank) {
        //$this->setRankMemberLegion($mat, $legionName, $rank);
    }
    
    public function removeMemberLegion($mat, $legionName) {
        //throw new Exception("Undefined method");        
    }

    public function setRankMemberLegion($pseudo, $legionName, $rank) {
   /*     
        $pseudo = utf8_clean_string($pseudo);
        $legion = utf8_clean_string($legionName);        
        
        $id = $this->forum->selectPerso($pseudo);
        $legion = $this->forum->selectLegions($legion);
        
        $grp_list = array();
        
        
        foreach($legion as $groupe) {  
                    $grp_list = $groupe[0];
        }        
        
        $this->forum->removeGroup($id, implode(",", $grp_list));
        
        $this->forum->addGroup($id,$groupes[$race]["Base"]);
        
        if($grade >= 4) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }            
            
            if(isset($groupes[$race]["Chef"])) {
                $this->forum->addGroup($id,$groupes[$race]["Chef"]);
            }
        } else if($grade >= 3 && $galon >= 2) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }
        }   */                 
    }
        
    
}

/*$ef = new EwoForum(5);

var_dump($ef->isBlank('Ganesh'));*/