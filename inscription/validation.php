<?php
/**
 * Inscription - Validation du lien d'activation
 *
 * Permet de valider le lien recu en email a l'inscription d'un utilisateur
 * et renvoi un nouvel email
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */


session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

if (isset($_GET['code']) && isset($_GET['nom']) && isset($_GET['email'])){

// Paramètres de connexion à la base de données
$ewp = bdd_connect('ewo');

$code_validation = mysql_real_escape_string($_GET['code']);
$nom = mysql_real_escape_string($_GET['nom']);
$email = mysql_real_escape_string($_GET['email']);

// Vérifier que la validation n'ai pas deja été faite
$verif = mysql_query("SELECT droits FROM `utilisateurs` WHERE nom = '$nom' AND codevalidation = '$code_validation'");
$verif = mysql_fetch_array ($verif);

$verif = $verif['droits'];
$verif = $verif[0];

if($verif==1){
	$titre = "Compte actif";
	$text = "Votre compte est deja actif";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}

	$verif_compte = mysql_query("SELECT nom, codevalidation FROM `utilisateurs` WHERE email = '$email' AND codevalidation = '$code_validation'");
	if (mysql_fetch_row($verif_compte)){
		$msg = "Votre compte vient d'être validé, vous pouvez maintenant accéder à votre page de jeu.";
	
	
	//modification de la bdd pour valider le compte
	
	$sql_users = mysql_query("UPDATE utilisateurs SET droits=1000 WHERE email = '$email'");
	
	//mail de confirmation
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
								<p>Votre compte $nom est &agrave; pr&eacute;sent actif</p>
								<p>Vous pouvez d&eacute;sormais jouer en vous connectant sur : </p>
								<a href='http://".$_URL."/'>Ewo le monde</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='center'  style='background-color:#B0B0B0;font-size:0.8em;'>[Ewo] ".$_URL." &copy; </td>
	</tr>
</body></html>";

     if(mail($email, '[Ewo] Votre compte est valide', $message, $headers)){
     		$titre = "Compte actif";
				$text = "Votre compte est d&eacute;sormais actif, un mail de confirmation vient de vous parvenir sur ".$email;
				$root = "..";
				$lien = "../";
				gestion_erreur($titre, $text, $root, $lien);
     }else{	
			$titre = "Erreur d'envoi'";
			$text = "Le message n'a pas pu etre envoy&eacute;'.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);
     }
	}else{
		$titre = "Erreur de compte";
		$text = "Ce compte n'existe pas'.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
	}
mysql_close($ewo);
}else{
		$titre = "Op&eacute;ration non comprise";
		$text = "Op&eacute;ration non comprise, il nous est impossible de valider votre compte utilisateur.";
		$root = "..";
		$lien = "..";
		gestion_erreur($titre, $text, $root, $lien);
}
?>
