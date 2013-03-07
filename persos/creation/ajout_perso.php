<?php

require_once __DIR__ . '/../../conf/master.php';

include_once(SERVER_ROOT. "/persos/fonctions.php");

include_once(SERVER_ROOT."/persos/creation/controle_persos.php");

    ControleAcces('utilisateur',1);

    $controle = controleCreationPerso($utilisateur_id);

    if(!isset($_POST['gameplay'])) {
            $_SESSION['erreur']['perso'] = "Veuiller choisir un gameplay.";
            header("location: .");		
    }

    if(!isset($_POST['race'])) {
            $_SESSION['erreur']['perso'] = "Veuiller choisir un camp.";
            header("location: .");		
    }	

    if($_POST['race'] != $controle['camp'] && $controle['camp'] != "") {
            $_SESSION['erreur']['perso'] = "Multicamps interdit!";
            header("location: .");				
    }

    if($_POST['race'] != 'ange' && $_POST['race'] != 'demon' && $_POST['race'] != 'humain') {
            $_SESSION['erreur']['perso'] = "Gest lost, kthxbyebbq.";
            header("location: .");		
    }

    if($_POST['gameplay'] == 'T3' && !$controle['creationT3']) {
            $_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer de perso type T1!";
            header("location: .");		
    }

    if($_POST['gameplay'] == 'T41' && !$controle['creationT4']) {
            $_SESSION['erreur']['perso'] = "Vous ne pouvez plus créer de perso type T4!";
            header("location: .");		
    }	

    if($_POST['gameplay'] != 'T3' && $_POST['gameplay'] != 'T41') {
            $_SESSION['erreur']['perso'] = "Veuillez, monsieur, cesser de magouiller les variables du formulaire";
            header("location: .");		
    }	

    if(!$controle['peutCreer']) {
            $_SESSION['erreur']['perso'] = "La limite du nombre de persos sert à limiter le nombre de persos";
            header("location: .");		
    }

    $gameplay = $_POST['gameplay'];
    $race 	  = $_POST['race'];

    checkPseudo($_POST['nom1']);

    if(!isset($_POST['sexe1']) || ($_POST['sexe1'] != '1' && $_POST['sexe1'] != '2' && $_POST['sexe1'] != '3')) {
        $_SESSION['erreur']['perso'] = "Vous n'avez pas choisi votre sexe (profitez, IRL c'est moins simple)";
        header("location: .");            
    }        

    $perso_type = 3;

    if($gameplay == "T4") {
        $perso_type = 4;
        checkPseudo($_POST['nom2']);
        checkPseudo($_POST['nom3']);
        checkPseudo($_POST['nom4']);

        if(!isset($_POST['sexe2']) || ($_POST['sexe2'] != '1' && $_POST['sexe2'] != '2' && $_POST['sexe2'] != '3') ||         
           !isset($_POST['sexe3']) || ($_POST['sexe3'] != '1' && $_POST['sexe3'] != '2' && $_POST['sexe3'] != '3') ||
           !isset($_POST['sexe4']) || ($_POST['sexe4'] != '1' && $_POST['sexe4'] != '2' && $_POST['sexe4'] != '3')) {

            $_SESSION['erreur']['perso'] = "Vous n'avez pas choisi votre sexe (profitez, IRL c'est moins simple)";
            header("location: .");    

        }            
    }    

    checkClass($gameplay, $race);

    function checkPseudo($pseudo) {
        if(preg_match("/^([[:alnum:]'àâéèêôùûç[:blank:]-]{1,75})$/i", $pseudo) != 1) {
                $_SESSION['erreur']['perso'] = "Votre pseudo contient des caractères interdits";
                header("location: .");	                
        }

        if(persos\creation\CreationPerso::PseudoExists($pseudo)) {
                $_SESSION['erreur']['perso'] = "Le pseudo $pseudo existe déjà";
                header("location: .");	         
        }

    }

    function checkClass($gameplay, $race) {
            if(!isset($_POST['choixclasse1' . $race]) || $_POST['choixclasse1' . $race] == '') {
                $_SESSION['erreur']['perso'] = "Vous n'avez pas choisi votre classe";
                header("location: .");	                    
            }
			
            if($gameplay == 'T4') {
                if(!isset($_POST['choixclasse2' . $race]) || $_POST['choixclasse2' . $race] == '' ||
                   !isset($_POST['choixclasse3' . $race]) || $_POST['choixclasse3' . $race] == '' ||   
                   !isset($_POST['choixclasse4' . $race]) || $_POST['choixclasse4' . $race] == '') {
                    $_SESSION['erreur']['perso'] = "Vous n'avez pas choisi votre classe";
                    header("location: .");	                    
                }                      
            }   

			
    }


    // Paramètres de connexion à la base de données
    //$ewo_bdd = bdd_connect('ewo');

    switch($race) {
        case 'humain':
            $perso_race = 1;
            break;
        case 'ange':
            $perso_race = 3;
            break;
        case 'demon':
            $perso_race = 4;
            break;        
    }

	
	
    $include_forum = true;
    include (SERVER_ROOT . '/lib/forum/ewo_forum.php');
    
    $forum = new EwoForum($utilisateur_id);
    
    $perso1 = new persos\creation\CreationPerso();

    $perso1->Nom = $_POST['nom1'];
    $perso1->Race = $race;
    $perso1->UtilisateurId = $utilisateur_id;
    $perso1->Gameplay = $gameplay;
    $perso1->Sexe = $_POST['sexe1'];
	
	$perso1->Classe = $_POST['choixclasse1' . $race];


    $perso1->Save();

    $forum->createPerso($perso1->Nom, $_SESSION['utilisateur']['mail'], $_SESSION['utilisateur']['passwd']);
		
    $forum->setRaceGrade($perso1->Nom, $perso1->Camp, 0, 1);

    if($gameplay == 'T4') {
        $perso2 = new persos\creation\CreationPerso();
        $perso3 = new persos\creation\CreationPerso();
        $perso4 = new persos\creation\CreationPerso();

        $perso2->Nom = $_POST['nom2'];
        $perso2->Sexe = $_POST['sexe2'];

        $perso3->Nom = $_POST['nom3'];
        $perso3->Sexe = $_POST['sexe3'];

        $perso4->Nom = $_POST['nom4'];
        $perso4->Sexe = $_POST['sexe4'];    

        $perso2->Race = $race;
        $perso2->UtilisateurId = $utilisateur_id;
        $perso2->Gameplay = $gameplay;

        $perso3->Race = $race;
        $perso3->UtilisateurId = $utilisateur_id;
        $perso3->Gameplay = $gameplay;

        $perso4->Race = $race;
        $perso4->UtilisateurId = $utilisateur_id;
        $perso4->Gameplay = $gameplay;    
		
		$perso2->Classe = $_POST['choixclasse2' . $race];
		$perso3->Classe = $_POST['choixclasse3' . $race];
		$perso4->Classe = $_POST['choixclasse4' . $race];		
		
        $perso2->Save();    
        $perso3->Save();   
        $perso4->Save();   
        
        $forum->createPerso($perso2->Nom, $_SESSION['utilisateur']['mail'], $_SESSION['utilisateur']['passwd']);        
        $forum->createPerso($perso3->Nom, $_SESSION['utilisateur']['mail'], $_SESSION['utilisateur']['passwd']);
        $forum->createPerso($perso4->Nom, $_SESSION['utilisateur']['mail'], $_SESSION['utilisateur']['passwd']);

        $forum->setRaceGrade($perso2->Nom, $perso2->Camp, 0, 1);        
        $forum->setRaceGrade($perso3->Nom, $perso3->Camp, 0, 1);   
        $forum->setRaceGrade($perso4->Nom, $perso4->Camp, 0, 1);   
    }

    $forum->lierComptes($_SESSION['utilisateur']['mail']);

   /* $_SESSION['temp']['perso_nom'] = $perso_nom;
    $_SESSION['temp']['perso_race'] = $perso_race;
    $_SESSION['temp']['perso_bg'] = $perso_bg;
    $_SESSION['temp']['perso_avatar'] = $avatar;

    $_SESSION['persos']['inc']		+= 1;
    $inc = $_SESSION['persos']['inc'];
    $_SESSION['persos']['id'][$inc]				= $id_perso ;
    $_SESSION['persos']['nom'][$inc]			= $perso_nom ;
    $_SESSION['persos']['race'][$inc]			= $race_id ;
    $_SESSION['persos']['race']['nom'][$inc]	= $nom_race ;
    $_SESSION['persos']['grade'][$inc]			= $grade_id ;
    $_SESSION['persos']['grade']['nom'][$inc]	= $nom_grade ;
    $_SESSION['persos']['faction']['id'][$inc]	= 0 ;
    $_SESSION['persos']['date_tour'][$inc]		= 0 ;
    $_SESSION['persos']['type'][$inc]			= $perso_type;
    $_SESSION['persos']['camp'][$inc]			= $perso_race;

    $_SESSION['temp']['perso_inc'] = $inc;

    echo "<script language='javascript' type='text/javascript' >document.location='../persos/apercu_perso.php'</script>";exit;
*/
 	$titre = "Personnage(s) crée(s)";
	$text = "La création du ou des personnages a été faite, vous pouvez vous reconnecter pour les jouer";
	$lien = SERVER_URL . "/session.php";
	gestion_erreur($titre, $text, $lien);
?>
