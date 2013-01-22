<?php

if(!isset($root_urlt)) {
    //$root_url = '../..';
}

if(!defined('IN_PHPBB')) {
    define('IN_PHPBB', true);
}

$phpEx = 'php';
$phpbb_root_path = $root_url.'/forum/';

if(isset($include_forum)) {

    require_once($root_url.'/forum/common.php');

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

    
    public function createPerso($pseudo,$password) {
        
    }
    
    public function setRace($pseudo,$race) {
        
    }
    
    public function setRank($perso_id) {
        
        /*global $groupes;
        
        $forum_officier = 0;
        $forum_sousofficier = 0;
        
        if($grade == 5) {
            $forum_officier = true;
        }
        
        if($grade >= 4 || ($grade == 3 && $galon == 2)) {
            $forum_sousofficier = true;
        }
        
        if($forum_officier);*/
    }
    
    public function changePasswords($password) {
        $hash = phpbb_hash($password);

        $liste_persos = $this->jeu->listePersos($this->id);

        $this->forum->setHash($liste_persos,$hash);
    }
    
    public function emptyPassword() {
        
    }
    
    public  function isBlank($pseudo) {
        return $this->forum->isBlankPassword($pseudo);
    }
        
    
}

/*$ef = new EwoForum(5);

var_dump($ef->isBlank('Ganesh'));*/