<?php
/**
 * Mail - Permet l'envoie d'un mail via ajax
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package mail
 */
 
include("mail.php");

// Vérification que les info soient bien passé en GET
if(!empty($_GET['to']) && !empty($_GET['subject']) && !empty($_GET['corps']) && !empty($_GET['type']){
	// Reconstruction du tableau
	$to = unserialize($_GET['to']);
	if (is_array($to) && send_mail($to,$_GET['subject'],$_GET['corps'],$_GET['type']) == true){
		echo "ok";
	}else{
		echo "erreur";
	}
}

?>
