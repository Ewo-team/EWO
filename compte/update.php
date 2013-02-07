<?php

namespace compte;

//-- Header --

require_once __DIR__ . '/../conf/master.php';
/* -- Connexion basic requise -- */
ControleAcces('utilisateur', 1);

$utilisateur_id = $_SESSION['utilisateur']['id'];
$compte = new Compte($utilisateur_id);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'email':
            changeEmail($compte);
            break;
        case 'grille':
            changeGrille($compte);
            break;
        case 'pass':
            changePass($compte);
            break;
        case 'redirection':
            changeRedirect($compte);
            break;
        case 'rose':
            changeRose($compte);
            break;
        case 'template':
            changeTemplate($compte);
            break;
        case 'vacances':
            changeVacances($compte);
            break;        
    }
}

header('location:../compte/');

function changeEmail($compte) {
    if (isset($_POST['email'])) {

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        if ($email !== false) {

            $compte->email = $email;

            //-- PHPBB integration, changement de l'adress mail
            /* if (!$sql_users = mysql_query("UPDATE phpbb_users SET user_email = '$email' WHERE username = '$utilisateur_name'")) {
              echo "erreur d'update dans le forum";
              } */
        } else {
            $titre = "Modification de compte.";
            $text = "Email non valide.";
            $root = "..";
            $lien = "..";
            gestion_erreur($titre, $text, $root, $lien);
        }
    } else {
        $titre = "Modification de compte.";
        $text = "Vous n'êtes pas autorisés à effectuer cette action.";
        $root = "..";
        $lien = "..";
        gestion_erreur($titre, $text, $root, $lien);
    }
}

function changeGrille($compte) {
    if (isset($_POST['grille']) AND $_POST['grille'] == 'ok') {
        $grille = 1;
    } else {
        $grille = 0;
    }


    $compte->grille = $grille;
}

function changePass($compte) {

    if (isset($_POST['pass_modif'])) {

        $passencode = $compte->encodePassword($_POST['pass_modif']);

        $compte->passwd = $passencode;
    } else {
        $titre = "Vous n'êtes pas autorisés à effectuer cette action.";
        $text = "Cet utilisateur n'existe pas.";
        $root = "..";
        $lien = "..";
        gestion_erreur($titre, $text, $root, $lien);
    }
}

function changeRedirect($compte) {
    if (!empty($_POST['redirection'])) {
        $redirection = $_POST['redirection'];
        if ((int) $redirection === 1 || (int) $redirection === 2 || (int) $redirection === 3) {
            $compte->redirection = $redirection;
        } else {
            $titre = "Modification de compte";
            $text = "Aucune page ne correspond a votre demande.";
            $lien = "..";
            $root = "..";
            gestion_erreur($titre, $text, $root, $lien);
        }
    } else {
        $titre = "Modification de compte";
        $text = "Vous n'êtes pas autorisés à effectuer cette action.";
        $lien = "..";
        $root = "..";
        gestion_erreur($titre, $text, $root, $lien);
    }
}

function changeRose($compte) {
    if (isset($_POST['rose']) AND $_POST['rose'] == '1') {
        $rose = 1;
    } else {
        $rose = 0;
    }

    $compte->rose = $rose;
}

function changeTemplate($compte) {
    if (!empty($_POST['template'])) {

        if (ctype_alnum($_POST['template'])) {
            $template = $_POST['template'];
            if (is_dir('../template/themes/' . $template)) {
                $compte->template = $template;
            } else {
                $titre = "Modification de compte";
                $text = "Ceci n'est pas un dossier.";
                $lien = "..";
                $root = "..";
                gestion_erreur($titre, $text, $root, $lien);
            }
        } else {
            $titre = "Modification de compte";
            $text = "Vous n'êtes pas autorisés à effectuer cette action.";
            $lien = "..";
            $root = "..";
            gestion_erreur($titre, $text, $root, $lien);
        }
    }
}

function changeVacances($compte) {

    if (!empty($_POST['v_action']) && !empty($_POST['check_vacances'])) {

        $action = $_POST['v_action'];

        $statut = $compte->statutVacances();

        if (false === $statut) {
            erreurVacances();
        } else {
            switch ($action) {
                case 'depart' :
                    //Pour partir il faut être en jeu
                    if ($statut != 'jeu') {
                        erreurVacances();
                    } else {
                        $compte->departVacances();
                    }
                    break;
                case 'retour' :
                    //Pour revenir il faut être en vacances
                    if ($statut != 'vacances') {
                        erreurVacances();
                    } else {
                        $compte->retourVacances();
                    }
                    break;
                default :
                    erreurVacances();
            }
        }
    } else {
        erreurVacances();
    }
}

function erreurVacances() {
    $titre = "Vacances";
    $text = "Vous n'êtes pas autorisés à effectuer cette action.";
    $lien = "../compte/options.php";
    $root = "..";
    gestion_erreur($titre, $text, $root, $lien);
}

?>
