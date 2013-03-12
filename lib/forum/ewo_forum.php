<?php

include 'include.php';	

class EwoForum {
    
    private $id;
    private $jeu;
    private $forum;
    
    public function __construct($user = 1) {
		
        $this->id = $user;
        $this->jeu = EwoForumDAO::getInstance("ewo");
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
    
    
	public function selectIdByName($pseudo) {
		$pseudo = utf8_clean_string($pseudo);
		$result = $this->forum->selectPerso($pseudo);
		return $result[0];
	}
	
	public function selectIdByMat($mat) {
		$name = $this->jeu->SelectPersoNameById($mat);
		return $this->selectIdByName($name);
	}
	
    public function selectPassEmail($email) {
        return $this->forum->getHashEmail($email);
    }
    
    public function lierComptes($email) {
        $this->forum->setMasterId($email);
    }
    
    public function setRaceGrade($id,$race,$grade,$galon) {
        
        if(!is_numeric($id)) {
            $clean = utf8_clean_string($id);
			
			$id = $this->forum->selectPerso($clean);

			$id = $id[0];
        }
		
		
               
        $grp_list = array();
        
        include 'EwoForumConfig.php';
        
        foreach($groupes as $race_index) {  
            foreach($race_index as $groupe) {  
                $grp_list[] = $groupe;
            }           
        }        
        
		//print_r($grp_list);
		//print_r($groupes);
		
        $this->forum->removeGroup($id, implode(",", $grp_list));
        		
        $this->forum->addGroup($id,$groupes[$race]["Base"]);
        
        if($grade >= 4) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }            
            
			if($grade >= 5) {
				if(isset($groupes[$race]["Chef"])) {
					$this->forum->addGroup($id,$groupes[$race]["Chef"]);
				}
			}
        } else if($grade >= 3 && $galon >= 2) {
            
            if(isset($groupes[$race]["Officier"])) {
                $this->forum->addGroup($id,$groupes[$race]["Officier"]);
            }
        }
		
		if(isset($rangs[$race][$grade])) {
			$this->forum->setRank($id, $rangs[$race][$grade]);
		} else {
			if(isset($rangs[$race][0])) {
				$this->forum->setRank($id, $rangs[$race][0]);
			} else {
				$this->forum->setRank($id, 0);
			}
		}
        
    }
    
    public function changePasswords($password) {
        $hash = phpbb_hash($password);

        $liste_persos = $this->jeu->listePersos($this->id);

        
        $liste_persos = array_map("utf8_clean_string", $liste_persos);
        
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