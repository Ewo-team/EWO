<?php

namespace compte\inscription;

/**
 * Inscription - Renvoie du lien d'activation
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */
require_once __DIR__ . '/../../conf/master.php';
//-- Header --
include(SERVER_ROOT . "/template/header_new.php");

//------------

$dao = InscriptionDAO::getInstance();

if (isset($_POST['email'])) {

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    $requete = $dao->SelectUserByEmail($email);
    
    if ($requete != false) {

        $droits = $requete['droits'][0];

        if ($droits == 0) {
            // Le compte n'est pas encore validé
            $codevalidation = $requete[1];
            $nom = $requete[2];


			$mail = \conf\Mail();
			
			$mail->Subject .= 'Code de validation de votre compte';

			
			$mail->AddTo($email, $nom);
						
			$mail->ParseTitle = "EWO";			
			$mail->ParseCorps = "<table width='100%' height='200px'>
								<tr>
									<td align='center' style='background: url(http://" . SERVER_URL . "/images/site/ewo_transparant.png) no-repeat 50% 50%'>
									Vous avez demand&eacute; de recevoir le mail de confirmation de votre compte $nom<br />
									il ne vous reste plus qu'&agrave; le valider<br />
									<a href='http://" . SERVER_URL . "/inscription/validation.php?code=$codevalidation&nom=$nom&email=$email'>Lien de validation</a>
									</td>
								</tr>
					</table>";
					
			$mail->Parse();

			$mail->Send();			
				

			echo "<div class='page_centre'><h2>Inscription</h2>
			<p>Vous allez recevoir un email de confirmation pour effectuer la validation de votre compte utilisateur.</p>
			<p>Le message a bien &eacute;t&eacute; envoy&eacute; sur " . $email . "</p>
			<p>[<a href='" . SERVER_URL . "/'>Retour</a>]</p></div>";

        } else {
            // Le compte est d�j� valid�
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
include(SERVER_ROOT . "/template/footer_new.php");
?>
