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
require_once __DIR__ . '/../../conf/master.php';

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


		$mail = \conf\Mail();
		
		$mail->Subject .= 'Votre compte est valide';

		
		$mail->AddTo($email, $nom);
					
		$mail->ParseTitle = "EWO";			
		$mail->ParseCorps = "<table width='100%' height='200px'>
							<tr>
								<td align='center' style='background: url(" . SERVER_URL . "/images/site/ewo_transparant.png) no-repeat 50% 50%'>
								<p>Votre compte $nom est &agrave; pr&eacute;sent actif</p>
								<p>Vous pouvez d&eacute;sormais jouer en vous connectant sur : </p>
								<a href='" . SERVER_URL . "/'>Ewo le monde</a>
								</td>
							</tr>
				</table>";
				
		$mail->Parse();

		$mail->Send();		


        $titre = "Compte actif";
        $text = "Votre compte est d&eacute;sormais actif, un mail de confirmation vient de vous parvenir sur " . $email;
        $root = "..";
        $lien = "../";
        gestion_erreur($titre, $text, $root, $lien);

} else {
    $titre = "Op&eacute;ration non comprise";
    $text = "Op&eacute;ration non comprise, il nous est impossible de valider votre compte utilisateur.";
    $root = "..";
    $lien = "..";
    gestion_erreur($titre, $text, $root, $lien);
}
?>
