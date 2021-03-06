<?php

namespace compte\inscription;
/**
 * Inscription - Confirmation de l'inscription
 *
 * Envoie du mail de confirmation de l'inscription.
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */
 
//-- Header --
include '../../conf/master.php';
include(SERVER_ROOT . "/template/header_new.php");
//------------

// Si la variable  $_SESSION['temp']['error'] est définie, alors stocke sa valeur dans la variable $msg_error .

//Envoie du mail de confirmation

	$mail = new \conf\Mail();
	
	$mail->Subject .= 'Code de validation de votre compte';
	
	
	$email = $_SESSION['temp']['mail'];
	$nom = htmlspecialchars($_SESSION['temp']['nom']);
	
	$mail->AddTo($email, $nom);
	
	$codevalidation = $_SESSION['temp']['code_validation'];
			
	$mail->ParseTitle = "EWO";			
	$mail->ParseCorps = "<table width='100%' height='200px'>
				<tr>
					<td align='center' style='background: url(".SERVER_URL."/images/site/ewo_transparant.png) no-repeat 50% 50%'>
					Votre compte $nom est bien enregistré<br />
					il ne vous reste plus qu'à le valider<br />
					<a href='".SERVER_URL."/compte/inscription/validation.php?code=$codevalidation&nom=$nom&email=$email'>Lien de validation</a>
					</td>
				</tr>
			</table>";
			
	$mail->Parse();

	$mail->Send();				

echo "<div class='page_centre'><h2>Inscription</h2>
<p>Vous allez recevoir un email de confirmation pour effectuer la validation de votre compte utilisateur.</p>
<p>Le message a bien &eacute;t&eacute; envoy&eacute; sur ".$email."</p>
<p>[<a href='".SERVER_URL."/'>Retour</a>]</p></div>";

//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
