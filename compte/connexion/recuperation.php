<?php
/**
 * Connexion - Formulaire de récupération du mot de passe
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
//-- Header --

require_once __DIR__ . '/../../conf/master.php';

$header['title'] = "Récupération mot de passe";
$header['desc'] = "Si par la plus grande mégarde vous avez égaré le bout de papier avec votre mot de passe dessus, c'est ici que ça se passe.";
include(SERVER_ROOT . "/template/header_new.php");
//------------
?>

<h2>vous avez oublié votre mot de passe ?</h2>

<!-- Debut du coin -->
<div>
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
		<td colspan="3">Un email avec un nouveau mot de passe vous sera envoyé.</td>
	</tr>
</table>
</form>
</div>


			<!-- fin conteneur -->
</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
