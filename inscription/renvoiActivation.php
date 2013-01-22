<?php
/**
 * Inscription - Renvoie du lien d'activation
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */

session_start();
$root_url = "..";
//-- Header --
include($root_url."/template/header_new.php");

//------------

if (isset($_POST['email'])){
	$ewp = bdd_connect('ewo');

	$email = mysql_real_escape_string($_POST['email']);
	$requete = mysql_query("SELECT droits, codevalidation, nom FROM `utilisateurs` WHERE email = '$email'");

	if($requete = mysql_fetch_row ($requete))
	{
	
		$droits = $requete[0];
		
		if($droits == 0){
			// Le compte n'est pas encore validé
			$codevalidation = $requete[1];
			$nom = $requete[2];
			
			$headers ='From: "EwoManager"<ewomanager@ewo.fr>'."\n";
			$headers .='Reply-To: ewomanager@ewo.fr'."\n";
			$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
			$headers .='Content-Transfer-Encoding: 8bit';
		
		
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
							Vous avez demand&eacute; de recevoir le mail de confirmation de votre compte $nom<br />
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
				// Problème de mail
				$titre = "Erreur d'envoi'";
				$text = "Le message n'a pas pu etre envoy&eacute;'.";
				$root = "..";
				$lien = "..";
				gestion_erreur($titre, $text, $root, $lien);
			}	
		
		
		} else {
			// Le compte est déjà validé
			$titre = "Compte actif";
			$text = "Votre compte est deja actif";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);			
		}	
	} else {
		// Le compte n'existe pas
		$titre = "Erreur de compte";
		$text = "Ce compte n'existe pas'.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);	  
	}
} else {
	// On affiche le formulaire de renvoi du code d'activation
		


	echo <<<HEREDOC
		<div id='inscription' align="center">
		<h2>Renvoi du mail de confirmation </h2>

		<!-- Debut du coin -->
		<div class="upperleft" id='coin_75'>
			<div class="upperright">
				<div class="lowerleft">
					<div class="lowerright">
					<!-- conteneur -->		
						
		<table border="0">
			<tr>
			<td width='450' align='center'>
				<form name='err' action="renvoiActivation.php" method="post">
				<table border="0" width="100%">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<th scope="row" align="right">E-Mail : </th>
						<td align="center"><input name="email" type="text" maxlength="64" value=""/></td>
					<td></td>
					</tr>

					<tr>
						<td colspan="3" align="center"><input type="submit" value="Valider" class="bouton" /></td>
					</tr>
				</table>
				</form>
			</td>
			<td><img src='../images/site/inscription.png' alt='inscription' /></td>
			</tr>
		</table>

					<!-- fin conteneur -->
					</div>
				</div>
			</div>
		</div>
		<!-- Fin du coin -->
HEREDOC;
}

//-- Footer --
include($root_url."/template/footer_new.php");
?>
