<?php
/**
 * Conact - script d'envoie du mail du formulaire de contact a l'équipe de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package contact
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

//-- envoie du formulaire à l'email de l'admin.

$mail = $_POST['mail'];
$sujet = $_POST['sujet'];
$auteur = $_POST['nom'];
$text = $_POST['text'];

$ip = $_SERVER["REMOTE_ADDR"];

$admin_mail = 'staff@ewo.fr';

if (!empty($mail) && !empty($sujet) && !empty($auteur) && !empty($text)){
	$headers ="From: Ewo<".$mail.">"."\n";
	$headers .="Reply-To: ".$mail."\n";
	$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
	$headers .='Content-Transfer-Encoding: 8bit';
			
	$message = "<html><head><title>EWO</title></head><body>
							<p>Message venant du formulaire de contact</p>
							<p>Sujet : $sujet</p>
							<p>Auteur : $auteur</p>
							<p>Mail : $mail</p>
							<p>Ip : $ip</p>
							<p>Message : $text</p>
							</body></html>";

	if(mail($admin_mail, '[Ewo] Formulaire de contact', $message, $headers)){
		$root_url = "..";
		include($root_url."/template/header_new.php");
			echo '<p>Votre message vient d\'être envoyé, les administrateurs du site feront au plus vite pour vous apporter une réponse.</p>';
		include("../template/footer_new.php");
	}else{	
		$root_url = "..";
		include($root_url."/template/header_new.php");
			echo '<p>Le message n\'a pu être envoyé</p>';
		include("../template/footer_new.php");
	}
}else{
		$titre = "Erreur dans le message";
		$text = "Il faut remplir les champs avant d'envoyer !";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
}
?>
