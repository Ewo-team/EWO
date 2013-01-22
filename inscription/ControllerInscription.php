<?php
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
 
session_start();
$root_url = '..';
include ($root_url."/conf/master.php");

// paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

// Mise sous variables des données récupérées
$nom = mysql_real_escape_string(ucfirst(htmlspecialchars(strip_tags($_POST['nom']),ENT_COMPAT, 'UTF-8')));
$email = mysql_real_escape_string($_POST['email']);
$pass = mysql_real_escape_string($_POST['pass_inscription']);
$confirm_pass = mysql_real_escape_string($_POST['confirm_pass']);
$jabberid = mysql_real_escape_string($_POST['jabberid']);

if($_TICKET == 1){
	$numero = mysql_real_escape_string($_POST['numero']);
}

$enregistrement = 0;
$msg_error = '';

// Vérifier que le champs ne soit pas vide.
if(empty($nom)){
	$msg_error .= "Veuillez mettre un pseudo.<br />";
}else{
	// Vérifier que le nom ne soit pas en bdd
	$verif_nom_existe = mysql_query("SELECT nom FROM `utilisateurs` WHERE nom = '$nom'");
	if (mysql_fetch_row($verif_nom_existe)){
		$msg_error .= "Ce nom de compte existe déjà.<br />";
		$enregistrement = 1;
	}else{
		$_SESSION['temp']['nom'] = $nom;
	}
}

// Vérification pour l'adresse E-Mail
if(empty($email)){
	$msg_error .= "Veuillez entrer un email<br />";
	$enregistrement = 1;
// Vérification de la validité de l'adresse email.
}else{
	$email = htmlspecialchars($email); // On rend inoffensives les balises HTML que le visiteur a pu rentrer

    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,5}$#i", $email)){
      $msg_error .= "E-mail non valide<br />";
			$enregistrement = 1;
    }else{
			// Vérification de l'existence en bdd de l'email
			$verif_email_existe = mysql_query("SELECT email FROM `utilisateurs` WHERE email = '$email'");
		if(mysql_fetch_row($verif_email_existe)){
			$msg_error .= "Cet E-mail est déjà utilisé.<br />";
			$enregistrement = 1;
		}else{
			$_SESSION['temp']['mail'] = $email;
		}
	}
}

// Vérification de la présence de caractères dans le chamsp mot de passe.
if(empty($pass)){
	$msg_error .= "Veuillez entrer un mot de passe<br />";
	$enregistrement = 1;
}

// Vérification de non différence des mots de passe.
if ($pass != $confirm_pass){
	$msg_error .= "Vos mots de passe ne sont pas identiques.<br />";
	$enregistrement = 1;
}else{
	$_SESSION['temp']['pass'] = $pass;
}

if($_TICKET == 1){
	// Vérifier que le ticket existe
	$verif_ticket_existe = mysql_query("SELECT numero FROM `invitations` WHERE numero = '$numero'");
	if (!mysql_fetch_row($verif_ticket_existe)){
		$msg_error .= "Ce ticket n'existe pas ou a déjà été utilisé.<br />";
		$enregistrement = 1;
	}else{
		$_SESSION['temp']['numero'] = $numero;
	}
}

mysql_close($ewo);

if($enregistrement == 0){
	//--génération des var pour les id uniques
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$datetime = "$date $time";
	
	$rand = rand(99999,9999999999);	
	$pass_forum = sha1($email.$datetime.$rand);
	$ip_adresse = $_SERVER['REMOTE_ADDR'];
	$session_id = sha1($email.$datetime.$rand.$ip_adresse);	
	

	$code_validation = md5($datetime).md5($rand).md5($nom);
	
	$_SESSION['temp']['nom'] = $nom;
	$_SESSION['temp']['mail'] = $email;
	$_SESSION['temp']['code_validation'] = $code_validation;
	
		//-- Hash du mot de pass
		$pass = hash('sha256', $pass);
		//--

	// Paramètres de connexion à la base de données
	$ewo = bdd_connect('ewo');
	
	$sql_users = mysql_query("INSERT INTO utilisateurs(id, nom, email, passwd, passwd_forum, date_enregistrement, jabberid, droits, options, codevalidation, session_id) VALUES('','$nom','$email','$pass','$pass_forum','$datetime','$jabberid','0000','','$code_validation', '$session_id')");
	
	$id_user = mysql_insert_id();
	
	mysql_query("INSERT INTO utilisateurs_option (utilisateur_id, bals_speed, template, redirection) VALUES('$id_user','0.5','defaut', '1')");
	
	if($sql_users == FALSE){
		$_SESSION['temp']['error'] = "Echec lors de la cr&eacute;ation, erreur de retour SQL, contacter un administrateur";
		echo "Echec lors de la cr&eacute;ation, erreur de retour SQL, contacter un administrateur";
		//<script language="javascript" type="text/javascript" >document.location="index.php"</script>
	}else{
		if($_TICKET == 1){
			//-- suppression du ticket d'invitation utilisé
			mysql_query("DELETE FROM invitations WHERE numero='$numero'");
		}
		?>
		<script language="javascript" type="text/javascript" >document.location="confirm_inscrip.php"</script>
		<?php
	}
	mysql_close($ewo);
}else{
	$_SESSION['temp']['error'] = $msg_error;
	header("location:index.php");
	//echo $msg_error;exit;
}
?>
