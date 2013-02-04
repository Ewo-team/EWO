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

$ip = $_SERVER["REMOTE_ADDR"];

$admin_mail = 'staff@ewo.fr';

$mail = new \conf\Mail();

if (!empty($mail) && !empty($sujet) && !empty($auteur) && !empty($text)){
	/*$headers ="From: Ewo<".$mail.">"."\n";
	$headers .="Reply-To: ".$mail."\n";
	$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
	$headers .='Content-Transfer-Encoding: 8bit';*/
			
	$messageHtml = "<h1>EWO</h1>
							<p>Message venant du formulaire de contact</p>
							<p>Sujet : $sujet</p>
							<p>Auteur : $auteur</p>
							<p>Mail : $mail</p>
							<p>Ip : $ip</p>
							<p>Message : $text</p>
							</body></html>";
							
	$messageText = "EWO\n
							Message venant du formulaire de contact\n
							Sujet : $sujet\n
							Auteur : $auteur\n
							Mail : $mail\n
							Ip : $ip\n
							Message : $text\n";							

	$mail->FromName = "Ewo";
	$mail->From = $email;
	$mail->To = 'leomaradan@gmail.com';
	$mail->ToName = 'Ganesh';
	$mail->Subject = '[Ewo] Formulaire de contact';
	
	if($mail->Send()) {
		echo 'OK';
	} else {
		print_r($mail->Log());
	}
	
	/*if(mail($admin_mail, '', $message, $headers)){
		include(SERVER_ROOT . "template/header_new.php");
			echo '<p>Votre message vient d\'être envoyé, les administrateurs du site feront au plus vite pour vous apporter une réponse.</p>';
		include(SERVER_ROOT . "/template/footer_new.php");
	}else{	
		include(SERVER_ROOT."/template/header_new.php");
			echo '<p>Le message n\'a pu être envoyé</p>';
		include(SERVER_ROOT . "/template/footer_new.php");
	}*/
}else{
		$titre = "Erreur dans le message";
		$text = "Il faut remplir les champs avant d'envoyer !";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
}
?>
