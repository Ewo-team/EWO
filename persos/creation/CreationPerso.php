<?php

namespace persos\creation;

include_once(SERVER_ROOT. "/persos/fonctions.php");

/**
 * Création d'un perso
 *
 * @author Leo
 */
class CreationPerso {
    public $Mat = null;    
    public $Nom;
    public $Race;
    public $UtilisateurId;
    public $Gameplay;
    public $Sexe;
    public $Grade = 0;
    public $Galon = 1;
    public $Xp = 0;
    
    public $RaceId;    
    public $Camp;

    
    public $Pv;
    public $RecupPv;
    public $MalusDef;
    public $Niveau;
    public $Mouvement;
    public $Pa;
    public $Des;
    public $Force;
    public $Perception;
    public $ResistanceMagique;

    public function Save() {
        
        $dao = CreationPersoDAO::getInstance();
   
        $gameplay = ($this->Gameplay == "T4") ? 4 : 3;
        
        $races = $dao->SelectRaceId(ucfirst($this->Race), $gameplay);
        
        $this->RaceId = $races['race_id'];
        $this->Camp = $races['camp_id'];

        if(!$this->Mat) {
            $this->Mat = 'null';
        }       
        
        $this->Mat = $dao->InsertPerso($this);

        $dao->InsertCaracAlter($this->Mat);
        

        
        //-- Alteration des caractéristiques de base



        //-- Caracteristique de base des races
        $caracs_base = caracs_base ($this->RaceId, 0);


        $this->Pv = $caracs_base['pv'];
        $this->RecupPv = $caracs_base['recup_pv'];
        $this->Niveau = $caracs_base['magie'];
        $this->Mouvement = $caracs_base['mouv'];
        $this->Pa = $caracs_base['pa'];
        $this->Des = floor($caracs_base['des']/2);
        $this->Force = $caracs_base['force'];
        $this->Perception = $caracs_base['perception'];
        $this->ResistanceMagique = $caracs_base['res_mag'];

        $dao->InsertCarac($this);



        /*
	//-- Code phpBB pour la gestion du pass et du login
	define('IN_PHPBB', true);
	$phpEx = 'php';
	$phpbb_root_path = $root.'/forum/';

	require($root.'/forum/common.php');
	require($root.'/forum/includes/functions_user.php');
	//--

	$utilisateur_pass = phpbb_hash($utilisateur_pass);

	require($root.'/persos/binding_forum.php');
	
	if(!LierPerso($perso_nom_fofo,$grade_id,$camp,$utilisateur_pass)) {
		// Le perso n'existais pas, il est ajouté

		// set user data
		$user_row = array(
			'username'		=> $perso_nom_fofo,
			'user_password'	=> $utilisateur_pass,
			'user_email'	=> $utilisateur_mail,
			'group_id'		=> $binding[$camp][0],
			'user_type'		=> USER_NORMAL);

		// add user
		if (user_add($user_row) == false) {
			$_SESSION['erreur']['perso'] = "Il est possible que ce nom de personnage existe déjà.";
			echo "<script language='javascript' type='text/javascript' >document.location='./creation_perso.php'</script>";
			exit;
		}
	}*/        
        
    }
    
 
    
    public static function PseudoExists($pseudo) {
        $dao = CreationPersoDAO::getInstance();
        
        return $dao->VerifyExistName($pseudo);
    }
}

?>
