<?php
/**
 * Connexion - Script de récupération du mot de passe
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
 
require_once __DIR__ . '/../conf/master.php';

include (SERVER_ROOT."/conf/generation_pass.php");

if (isset($_POST['email'])){

// Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');
$email = mysql_real_escape_string($_POST['email']);
$pass = \conf\Helpers::generatePassword();
$passencode = hash("sha256",$pass);

	$sql_users = mysql_fetch_array (mysql_query("SELECT nom FROM utilisateurs WHERE email = '$email'"));

	if(!empty($sql_users['nom'])){
		$sql_users = mysql_query("UPDATE utilisateurs SET passwd = '$passencode' WHERE email = '$email'");
		mysql_close($ewo);	
		//mail de confirmation
			$headers ='From: "EwoManager"<ewomanager@ewo.fr>'."\n";
		  $headers .='Reply-To: ewomanager@ewo.fr'."\n";
		  $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
		  $headers .='Content-Transfer-Encoding: 8bit';
			
			$message = "<html><head><title>EWO</title></head><body>
									<p>Votre compte : $email</p>
									<p>Votre nouveau password : $pass</p>
									<a href='http://$_URL'>Ewo le monde</a>
									<p>Vous pouvez modifier votre mot de passe dans la gestion de votre compte une fois connecté.</p>
									</body></html>";

		if(mail($email, '[Ewo] Votre nouveau password', $message, $headers))
		{
			$titre = "Récupération du mot de passe";
			$text = "Vous allez recevoir d'ici peu un mot de passe pour vous connecter sur le site.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);
		}else{	
			$titre = "Erreur d'envoi du mail";
			$text = "Le message n'a pu être envoyé.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);
		}
	}else{
		mysql_close($ewo);		
		$titre = "Erreur d'envoi du mail";
		$text = "Cet email n'existe pas.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
	}
}else{
	$titre = "Erreur d'envoi du mail";
	$text = "Opération non comprise, veuillez entrer un email valide.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
