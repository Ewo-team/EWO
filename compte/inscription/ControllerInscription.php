<?php

namespace compte\inscription;
/**
 * Inscription - Controle de l'inscription
 *
 * Permet de controler l'inscription d'un utilisateur afin d'éviter les doublons de nom, de mail,
 * la vérification du mot de passe, et la validation de charte
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */

require_once __DIR__ . '/../../conf/master.php';

// paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

$dao = \inscription\InscriptionDAO::getInstance();


// Mise sous variables des données récupérées
$nom = ucfirst(htmlspecialchars(strip_tags($_POST['nom']), ENT_COMPAT, 'UTF-8'));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$pass = $_POST['pass_inscription'];
$confirm_pass = $_POST['confirm_pass'];

if ($_TICKET == 1) {
    if (is_numeric($_POST['numero'])) {
        $numero = $_POST['numero'];
    }
}

$enregistrement = 0;
$msg_error = '';

// Vérifier que le champs ne soit pas vide.
if (empty($nom)) {
    $msg_error .= "Veuillez mettre un pseudo.<br />";
} else {
    // Vérifier que le nom ne soit pas en bdd
    $users = $dao->VerifyExistName($nom);

    if ($users) {
        $msg_error .= "Ce nom de compte existe déjà.<br />";
        $enregistrement = 1;
    } else {
        $_SESSION['temp']['nom'] = $nom;
    }
}

// Vérification pour l'adresse E-Mail
if (empty($email)) {
    $msg_error .= "Veuillez entrer un email<br />";
    $enregistrement = 1;
// Vérification de la validité de l'adresse email.
} else {

    if ($dao->VerifyExistEmail($email)) {
        $msg_error .= "Cet E-mail est déjà utilisé.<br />";
        $enregistrement = 1;
    } else {
        $_SESSION['temp']['mail'] = $email;
    }
}

// Vérification de la présence de caractères dans le chamsp mot de passe.
if (empty($pass)) {
    $msg_error .= "Veuillez entrer un mot de passe<br />";
    $enregistrement = 1;
}

// Vérification de non différence des mots de passe.
if ($pass != $confirm_pass) {
    $msg_error .= "Vos mots de passe ne sont pas identiques.<br />";
    $enregistrement = 1;
} else {
    $_SESSION['temp']['pass'] = $pass;
}

if ($_TICKET == 1) {

    if ($dao->VerifyExistEmail($numero)) {
        $msg_error .= "Ce ticket n'existe pas ou a déjà été utilisé.<br />";
        $enregistrement = 1;
    } else {
        $_SESSION['temp']['numero'] = $numero;
    }
}


if ($enregistrement == 0) {
    //--génération des var pour les id uniques
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $datetime = "$date $time";

    $rand = rand(99999, 9999999999);

    $ip_adresse = $_SERVER['REMOTE_ADDR'];
    $session_id = sha1($email . $datetime . $rand . $ip_adresse);


    $code_validation = md5($datetime) . md5($rand) . md5($nom);

    $_SESSION['temp']['nom'] = $nom;
    $_SESSION['temp']['mail'] = $email;
    $_SESSION['temp']['code_validation'] = $code_validation;

    //-- Hash du mot de pass
    $pass = hash('sha256', $pass);
    //--

    $sql_users = $dao->AddUser($nom, $email, $pass, $code_validation, $session_id);

    if ($sql_users == false || $sql_users == 0) {
        $_SESSION['temp']['error'] = "Echec lors de la cr&eacute;ation, erreur de retour SQL, contacter un administrateur";
        echo "Echec lors de la cr&eacute;ation, contacter un administrateur";
    } else {
        if ($_TICKET == 1) {
            //-- suppression du ticket d'invitation utilisé
            $this->dao->RemoveTicket($numero);
        }
        ?>
        <script language="javascript" type="text/javascript" >document.location="confirm_inscrip.php"</script>
        <?php

    }
    mysql_close($ewo);
} else {
    $_SESSION['temp']['error'] = $msg_error;
    header("location:index.php");
    //echo $msg_error;exit;
}
?>
