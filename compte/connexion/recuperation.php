<?php
/**
 * Connexion - Formulaire de récupération du mot de passe
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
//-- Header --
$root_url = "..";
$header['title'] = "Récupération mot de passe";
$header['desc'] = "Si par la plus grande mégarde vous avez égaré le bout de papier avec votre mot de passe dessus, c'est ici que ça se passe.";
include($root_url."/template/header_new.php");
//------------
?>

<h2>vous avez oublié votre mot de passe ?</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->

<div align='center'>
<form name='pass' action="recup_password.php" method="post">
<table>
	<tr>
		<td>Votre email : </td>
		<td><input type="text" name="email" size="20" value="" /></td>
		<td><input type="submit" value="Envoyer" class="bouton" /></td>
	</tr>
	<tr>
		<td colspan="3">Un email avec un nouveau mot de passe vous a été envoyé.</td>
	</tr>
</table>
</form>
</div>


			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
