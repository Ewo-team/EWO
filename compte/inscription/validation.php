<?php

namespace compte\inscription;

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
include '../../conf/master.php';

if (isset($_GET['code']) && isset($_GET['nom']) && isset($_GET['email'])) {

// Paramètres de connexion à la base de données
    $dao = InscriptionDAO::getInstance();

    $code_validation = (ctype_alnum($_GET['code'])) ? $_GET['code'] : 'null';
    $nom = ucfirst(htmlspecialchars(strip_tags($_GET['nom']), ENT_COMPAT, 'UTF-8'));
    $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

    $verif = $dao->SelectUserByCode($code_validation);


    if ($verif['nom'] != $nom || $verif['droits'][0] == 1) {
        $titre = "Compte actif";
        $text = "Ce compte n'existe pas, où est deja actif";
        $root = "..";
        $lien = "..";
        gestion_erreur($titre, $text, $root, $lien);
    }

    $msg = "Votre compte vient d'être validé, vous pouvez maintenant accéder à votre page de jeu.";


    $dao->ActiveCompte($code_validation);

    //mail de confirmation
    $headers = 'From: "EwoManager"<ewomanager@ewo.fr>' . "\n";
    $headers .='Reply-To: ewomanager@ewo.fr' . "\n";
    $headers .='Content-Type: text/html; charset="iso-8859-1"' . "\n";
    $headers .='Content-Transfer-Encoding: 8bit';

    $message = "<html><head><title>EWO</title></head><body>
<table width='800px'>
	<tr style='background-color:#B0B0B0'>
		<td colspan='3'><img src='http://" . $_URL . "/images/site/ewo_logo_mini.png'></td>
	</tr>
	<tr>
		<td width='15px' style='background-color:#B0B0B0'></td>
		<td>
			<table width='100%' height='200px'>
				<tr>
					<td align='center' style='background: url(http://" . $_URL . "/images/site/ewo_transparant.png) no-repeat 50% 50%'>
								<p>Votre compte $nom est &agrave; pr&eacute;sent actif</p>
								<p>Vous pouvez d&eacute;sormais jouer en vous connectant sur : </p>
								<a href='http://" . $_URL . "/'>Ewo le monde</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='center'  style='background-color:#B0B0B0;font-size:0.8em;'>[Ewo] " . $_URL . " &copy; </td>
	</tr>
</body></html>";

    if (mail($email, '[Ewo] Votre compte est valide', $message, $headers)) {
        $titre = "Compte actif";
        $text = "Votre compte est d&eacute;sormais actif, un mail de confirmation vient de vous parvenir sur " . $email;
        $root = "..";
        $lien = "../";
        gestion_erreur($titre, $text, $root, $lien);
    } else {
        $titre = "Erreur d'envoi'";
        $text = "Le message n'a pas pu etre envoy&eacute;'.";
        $root = "..";
        $lien = "..";
        gestion_erreur($titre, $text, $root, $lien);
    }
} else {
    $titre = "Op&eacute;ration non comprise";
    $text = "Op&eacute;ration non comprise, il nous est impossible de valider votre compte utilisateur.";
    $root = "..";
    $lien = "..";
    gestion_erreur($titre, $text, $root, $lien);
}
?>
