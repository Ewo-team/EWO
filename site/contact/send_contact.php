<?php

namespace site\contact;

/**
 * Conact - script d'envoie du mail du formulaire de contact a l'équipe de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package site\contact
 */

require_once __DIR__ . '/../../conf/master.php';

//-- envoie du formulaire à l'email de l'admin.

$email = $_POST['mail'];
$sujet = $_POST['sujet'];
$auteur = $_POST['nom'];
$text = $_POST['text'];

$utilisateur = 'Utilisateur non-logué';

if(isset($_SESSION['utilisateur']['nom'])) {
	$utilisateur = $_SESSION['utilisateur']['nom'];
}

$ip = $_SERVER["REMOTE_ADDR"];

$admin_mail = 'staff@ewo.fr';

$mail = new \conf\Mail();

if (!empty($mail) && !empty($sujet) && !empty($auteur) && !empty($text)){
			
	$messageHtml = "<h1>EWO</h1>
							<p>Message venant du formulaire de contact</p>
							<p>Sujet : $sujet</p>
							<p>Auteur : $auteur</p>
							<p>Mail : $email</p>
							<p>Utilisateur : $utilisateur</p>
							<p>Ip : $ip</p>
							<p>Message : $text</p>
							</body></html>";
							
	$messageText = "EWO\n
							Message venant du formulaire de contact\n
							Sujet : $sujet\n
							Auteur : $auteur\n
							Mail : $email\n
							Utilisateur : $utilisateur\n
							Ip : $ip\n
							Message : $text\n";							

	$mail->MessageHtml = $messageHtml;
	$mail->MessageText = $messageText;
							
	$mail->FromName = "Ewo";
	$mail->From = $email;
	$mail->Reply = $email;
	$mail->AddCc($email);
	
	$mail->AddTo($admin_mail);
	$mail->Subject = '[Ewo] Formulaire de contact';
	
	if($mail->Send()) {
		$titre = "Message envoyé";
		$text = "Votre message vient d\'être envoyé, les administrateurs du site feront au plus vite pour vous apporter une réponse.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);		
	} else {
		$titre = "Message non envoyé";
		$text = "En raison d\'un problème technique, le message n\'a pu être envoyé.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);	
	}
}else{
		$titre = "Erreur dans le message";
		$text = "Il faut remplir les champs avant d'envoyer !";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
}
?>
