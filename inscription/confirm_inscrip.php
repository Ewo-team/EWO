<?php
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
$root_url = "..";
include($root_url."/template/header_new.php");
//------------

// Si la variable  $_SESSION['temp']['error'] est définie, alors stocke sa valeur dans la variable $msg_error .

//Envoie du mail de confirmation
  $headers ='From: "EwoManager"<ewomanager@ewo.fr>'."\n";
  $headers .='Reply-To: ewomanager@ewo.fr'."\n";
  $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
  $headers .='Content-Transfer-Encoding: 8bit';

	$email = $_SESSION['temp']['mail'];
	$nom = htmlspecialchars($_SESSION['temp']['nom']);
	$codevalidation = $_SESSION['temp']['code_validation'];
			
	$message = "<html><head><title>EWO</title></head><body>
<table width='800px'>
	<tr style='background-color:#B0B0B0'>
		<td colspan='3'><img src='http://".$_URL."/images/site/ewo_logo_mini.png'></td>
	</tr>
	<tr>
		<td width='15px' style='background-color:#B0B0B0'></td>
		<td>
			<table width='100%' height='200px'>
				<tr>
					<td align='center' style='background: url(http://".$_URL."/images/site/ewo_transparant.png) no-repeat 50% 50%'>
					Votre compte $nom est bien enregistr&eacute;<br />
					il ne vous reste plus qu'&agrave; le valider<br />
					<a href='http://".$_URL."/inscription/validation.php?code=$codevalidation&nom=$nom&email=$email'>Lien de validation</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='center'  style='background-color:#B0B0B0;font-size:0.8em;'>[Ewo] www.".$_URL." &copy; </td>
	</tr>
</body></html>";

   if(mail($email, '[Ewo] Code de validation de votre compte', $message, $headers))
     {
       echo "<div class='page_centre'><h2>Inscription</h2>
       <p>Vous allez recevoir un email de confirmation pour effectuer la validation de votre compte utilisateur.</p>
       <p>Le message a bien &eacute;t&eacute; envoy&eacute; sur ".$email."</p>
       <p>[<a href='".$root_url."/'>Retour</a>]</p></div>";
     }else{
        echo "<div class='page_centre'><h2>Inscription</h2><p>Le message n'a pas pu être envoyé.</p>
		  <p>[<a href='".$root_url."/'>Retour</a>]</p></div>";
     }
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
