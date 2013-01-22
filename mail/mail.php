<?php
/**
 * Mail - Permet l'envoie de mail
 *
 * Envoie un mail en fonction d'un type : text ou html
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package mail
 */

/**
 * Envoie de mail avec template
 * @param $to array contenant les mails
 * @param $type : html ou text
 * @param $corps : contient le html du mail
 */
function send_mail($to,$subject,$corps,$type){
	// Plusieurs destinataires
	$liste_mail = '';
	foreach ($to as $mail){
		$liste_mail .= $mail.',';
	}
	$time = time();	
	$liste = rtrim($liste_mail,',');

	if($type == "html"){
		// Sujet
		$message  = "<html>
							<head>
							 <title>".$subject."</title>
							</head>
							<body>
						 		<div>".$corps."</div>
							</body>
     					 </html>";	

		// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// En-têtes additionnels
		$headers .= 'From: contact@ewo-le-monde.fr' . "\r\n" .
		'Reply-To: contact@ewo-le-monde.fr' . "\r\n";
		
		// Envoi du mail
		if (mail($liste, $subject, $message, $headers)){
			//return true;
		}else{
			//return false;
		}
	}elseif($type == "text"){
		// Le message \n a chaque ligne
		$message = $corps;

		// Dans le cas où nos lignes comportent plus de 70 caractères, nous les coupons en utilisant wordwrap()
		$message = wordwrap($message, 70);
		
		$headers = 'From: contact@ewo-le-monde.fr' . "\r\n" .
		'Reply-To: contact@ewo-le-monde.fr' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

		// Envoi du mail
		if (mail($liste, $subject, $message, $headers)){
		//return true;
		}else{
		//return false;
		}
		//return true;
	}else{
		//return false;
	}
}
?>
